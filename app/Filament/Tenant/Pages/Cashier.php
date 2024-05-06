<?php

namespace App\Filament\Tenant\Pages;

use App\Filament\Tenant\Pages\Traits\CartInteraction;
use App\Filament\Tenant\Pages\Traits\TableProduct;
use App\Models\Tenants\CartItem;
use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Rules\CheckProductStock;
use App\Rules\ShouldSameWithSellingDetail;
use App\Services\Tenants\SellingService;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionSupport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Cashier extends Page implements HasForms, HasTable
{
    use CartInteraction, TableProduct;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static string $view = 'filament.tenant.pages.cashier';

    public Collection $cartItems;

    protected static string $layout = 'filament-panels::components.layout.base';

    public array $cartDetail = [];

    public CollectionSupport $paymentMethods;

    public CollectionSupport $members;

    public float $tax;

    public string $currency;

    public float $sub_total = 0;

    public float $total_price = 0;

    public function mount()
    {
        $this->tax = (float) Setting::get('default_tax', 0);

        $this->currency = Setting::get('currency', 'IDR');

        $this->cartItems = CartItem::query()
            ->select('*')
            ->with('product')
            ->orderByDesc('created_at')
            ->cashier()
            ->get();

        $this->sub_total = 0;

        $this->cartItems->each(function (CartItem $item) {
            $this->sub_total += $item->price;
        });

        $this->total_price = $this->sub_total + ($this->sub_total * $this->tax / 100);

        $this->paymentMethods = PaymentMethod::query()
            ->select('id', 'name')
            ->get()
            ->pluck('name', 'id');

        $this->members = Member::query()
            ->select('id', 'name')
            ->get()
            ->pluck('name', 'id');

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
                TextInput::make('customer_number'),
                Select::make('member_id')
                    ->label('Member')
                    ->options($this->members)
                    ->searchable(),
                RichEditor::make('note'),
            ])
            ->statePath('cartDetail')
            ->model(Selling::class);
    }

    public function storeCart(): void
    {
        $this->fillMember();
        $this->fillPayemntMethod();

        $this->dispatch('close-modal', id: 'edit-detail');
    }

    private function fillPayemntMethod()
    {
        $paymentMethod = $this->paymentMethods->filter(function (string $value, int $key) {
            return $key == $this->cartDetail['payment_method_id'];
        })->first();
        $this->cartDetail['payment_method_label'] = $paymentMethod;
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
        $request = array_merge($this->cartDetail, [
            'products' => $this->cartItems->map(function (CartItem $cartItem) {
                return [
                    'product_id' => $cartItem->product_id,
                    'qty' => $cartItem->qty,
                ];
            })->toArray(),
        ]);
        $validator = Validator::make($request, [
            'payed_money' => ['required', 'min:1'],
            'fee' => ['numeric'],
            'payed_money' => ['required', 'gte:total_price'],
            'total_price' => ['required_if:friend_price,true', 'numeric'],
            'total_qty' => ['required_if:friend_price,true', 'numeric', new ShouldSameWithSellingDetail('qty', $request['products'])],
            'friend_price' => ['required', 'boolean'],
            'products' => ['array'],
            'products.product_id' => ['required', 'exists:products,id'],
            'products.price' => ['required_if:friend_price,true', 'numeric'],
            'products.qty' => ['required', 'numeric', 'min:1', new CheckProductStock],
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->messages()->toArray());
        }
        $data = array_merge($sellingService->mapProductRequest($request), $request);
        $sellingService->create($data);
        CartItem::query()
            ->cashier()
            ->delete();

        Notification::make()
            ->title(__('Transaction created'))
            ->success()
            ->send();

        $this->mount();

        $this->dispatch('close-modal', id: 'proceed-the-payment');
    }
}
