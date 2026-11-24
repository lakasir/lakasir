<?php

namespace App\Filament\Tenant\Pages;

use App\Features\Member as FeaturesMember;
use App\Features\Voucher;
use App\Filament\Tenant\Pages\Traits\CartInteraction;
use App\Filament\Tenant\Pages\Traits\TableProduct;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\About;
use App\Models\Tenants\CartItem;
use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Table;
use App\Models\Tenants\Voucher as TenantsVoucher;
use App\Services\Tenants\SellingService;
use App\Services\VoucherService;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\RawJs;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionSupport;

class Cashier extends Page implements HasForms, HasTable
{
    use CartInteraction, HasTranslatableResource, RefreshThePage, TableProduct;

    public static ?string $label = 'POS';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static string $view = 'filament.tenant.pages.cashier';

    public static function canAccess(): bool
    {
        return can('create selling');
    }

    public Collection $cartItems;

    public Collection $availableVoucher;

    protected static string $layout = 'filament-panels::components.layout.base';

    public array $cartDetail = [];

    public array $paymentMethods;

    public CollectionSupport $members;

    public float $tax;

    public string $currency;

    public string $locale;

    public float $sub_total = 0;

    public float $total_price = 0;

    public ?About $about;

    public ?Collection $tableOption;

    private float $discount_price = 0;

    public function mount()
    {
        $this->about = About::first() ?? null;

        $this->tax = (float) Setting::get('default_tax', 0);

        $this->currency = Setting::get('currency', 'IDR');

        $this->locale = Profile::get()->locale ?? 'en';

        $this->cartItems = CartItem::query()
            ->select('*')
            // priceUnit is read once per line in calculateTotalPrice(); the
            // price-setting panel then reads product->priceUnits per line.
            ->with(['product.priceUnits', 'priceUnit'])
            ->orderByDesc('created_at')
            ->cashier()
            ->get();
        $vouchers = TenantsVoucher::query()
            ->where('minimal_buying', '<=', $this->cartItems->sum('price'))
            ->where('start_date', '<=', today()->format('Y-m-d'))
            ->where('expired', '>=', today()->format('Y-m-d'))
            ->get();

        $this->availableVoucher = $vouchers;

        $this->calculateTotalPrice();

        $this->paymentMethods = PaymentMethod::query()
            ->select('id', 'name', 'is_credit')
            ->get()
            ->toArray();

        $this->members = Member::query()
            ->select('id', 'name')
            ->get()
            ->pluck('name', 'id');

        $this->tableOption = Table::select('id', 'number')->get();

        $this->storeCartForm->fill([
            'payment_method_id' => 1,
            'total_price' => $this->total_price,
            'friend_price' => false,
        ]);

        $this->fillPayemntMethod();
    }

    protected function getForms(): array
    {
        return [
            'storeCartForm',
        ];
    }

    public function storeCartForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->visible(hasFeatureAndPermission(FeaturesMember::class))
                    ->label('Member')
                    ->getSearchResultsUsing(function (string $search): array {
                        return Member::query()
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->hiddenLabel()
                    ->extraAttributes([
                        'id' => 'memberSelect',
                        'class' => 'hidden',
                    ])
                    ->searchable(),
                RichEditor::make('note')
                    ->hiddenLabel()
                    ->extraAttributes([
                        'id' => 'noteInput',
                        'class' => 'hidden',
                    ]),
                TextInput::make('voucher')
                    ->hiddenLabel()
                    ->extraAttributes([
                        'id' => 'voucherInput',
                        'class' => 'hidden',
                    ])
                    ->visible(hasFeatureAndPermission(Voucher::class)),
                TextInput::make('discount_price')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix(Setting::get('currency', 'IDR'))
                    ->extraAttributes([
                        'id' => 'discountInput',
                        'class' => 'hidden',
                    ])
                    ->hiddenLabel()
                    ->label(__('Manual Discount')),
            ])
            ->statePath('cartDetail')
            ->model(Selling::class);
    }

    public function storeCart(): void
    {
        if ($this->cartDetail['voucher']) {
            $this->validateVoucher($this->cartDetail['voucher']);
        }

        if ($discount_price = str_replace(',', '', $this->cartDetail['discount_price'])) {
            $this->cartItems->each(function (CartItem $item) {
                if ($item->discount_price && $item->discount_price > 0) {
                    $this->discount_price += $item->discount_price;
                }
            });
            $this->discount_price += floatval($discount_price);
            // Tax is charged on the price after discounts, as on the server (SellingService::calculate()).
            $taxable = max(0, $this->sub_total - $this->discount_price);
            $this->total_price = $taxable + ($taxable * $this->tax / 100);
        }
        $this->fillMember();
        $this->fillPayemntMethod();

        $this->dispatch('close-modal', id: 'edit-detail');
    }

    private function fillPayemntMethod()
    {
        $paymentMethod = collect($this->paymentMethods)->filter(function ($value, int $key) {
            return $value['id'] == $this->cartDetail['payment_method_id'];
        })->first();
        if (isset($paymentMethod['name'])) {
            $this->cartDetail['payment_method_label'] = $paymentMethod['name'];
        }
    }

    private function fillMember()
    {
        $member = $this->members->filter(function (string $value, int $key) {
            return $key == $this->cartDetail['member_id'];
        })->first();
        $this->cartDetail['member_label'] = $member;
    }

    public function proceedThePayment(SellingService $sellingService): void
    {
        $this->cartDetail = array_merge($this->cartDetail, [
            'total_price' => $this->total_price,
        ]);

        $request = array_merge($this->cartDetail, [
            'discount_price' => floatval(str_replace(',', '', $this->cartDetail['discount_price'])),
            'products' => $this->cartItems->map(function (CartItem $cartItem) {
                return [
                    'product_id' => $cartItem->product_id,
                    'qty' => $cartItem->qty,
                    'price' => $cartItem->price,
                    'discount_price' => $cartItem->discount_price,
                    'price_unit_id' => $cartItem->price_unit_id,
                ];
            })->toArray(),
        ]);

        // Same rules and total checks as the API (TransactionSellingStoreRequest).
        $selling = $sellingService->checkout($request);
        CartItem::query()
            ->cashier()
            ->delete();

        Notification::make()
            ->title(__('Transaction created'))
            ->success()
            ->send();

        $this->mount();

        $this->dispatch('selling-created', selling: $selling->load('sellingDetails.product', 'table'));
    }

    public function assignVoucher(string $code)
    {
        $this->validateVoucher($code) ? $this->cartDetail['voucher'] = $code : null;
    }

    public function removeVoucher()
    {
        $this->cartDetail['voucher'] = null;
        $this->discount_price = 0;
        $this->calculateTotalPrice();
    }

    private function validateVoucher(string $code): bool
    {
        $voucherService = new VoucherService;
        $voucher = $voucherService->applyable($code, $this->total_price);
        if (! $voucher) {
            Notification::make('voucher_not_found')
                ->title(__('Voucher not found'))
                ->warning()
                ->send();

            return false;
        }

        $this->cartItems->each(function (CartItem $item) {
            if ($item->discount_price && $item->discount_price > 0) {
                $this->discount_price += $item->discount_price;
            }
        });
        $this->discount_price += $voucher->calculate();
        // Tax is charged on the price after discounts, as on the server (SellingService::calculate()).
        $taxable = max(0, $this->sub_total - $this->discount_price);
        $this->total_price = $taxable + ($taxable * $this->tax / 100);

        return true;
    }

    private function calculateTotalPrice()
    {
        $this->sub_total = 0;

        $this->discount_price = 0;
        $this->cartItems->each(function (CartItem $item) {
            $priceUnit = $item->priceUnit?->selling_price;
            if ($priceUnit) {
                $priceUnit = $priceUnit * $item->qty;
            }

            $this->sub_total += $priceUnit ?? $item->price;
            if ($item->discount_price && $item->discount_price > 0) {
                $this->discount_price += $item->discount_price;
            }
        });

        // Tax is charged on the price after discounts, as on the server (SellingService::calculate()).
        $taxable = max(0, $this->sub_total - $this->discount_price);
        $this->total_price = $taxable + ($taxable * $this->tax / 100);
    }
}
