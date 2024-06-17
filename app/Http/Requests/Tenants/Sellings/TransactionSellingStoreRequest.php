<?php

namespace App\Http\Requests\Tenants\Sellings;

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Rules\CheckProductStock;
use App\Rules\ShouldSameWithSellingDetail;
use App\Services\Tenants\SellingService;
use App\Services\VoucherService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TransactionSellingStoreRequest extends FormRequest
{
    public function __construct(
        private SellingService $sellingService,
        private VoucherService $voucherService
    ) {

    }

    public function authorize(): bool
    {
        if (Setting::get('cash_drawer_enabled', false)) {
            $lastOpenedCashDrawer = CashDrawer::lastOpened()->first();
            if (! $lastOpenedCashDrawer) {
                throw ValidationException::withMessages([
                    'cash_drawer' => 'Cash drawer is not opened',
                ]);
            }
        }

        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge($this->sellingService->mapProductRequest($this->all()));
    }

    public function rules(): array
    {
        $request = $this->all();
        $pMethod = PaymentMethod::find($request['payment_method_id']);
        $totalPrice = $request['total_price'] - ($request['discount_price'] ?? 0) - ($request['total_discount_per_item'] ?? 0);

        return [
            'fee' => ['numeric'],
            'payed_money' => [
                'required',
                ! $pMethod->is_credit ? 'gte:'.$totalPrice : null,
                Rule::requiredIf(fn () => ! $pMethod->is_credit),
            ],
            'total_price' => ['required_if:friend_price,true', 'numeric'],
            'total_qty' => ['required_if:friend_price,true', 'numeric', new ShouldSameWithSellingDetail('qty', $this->products)],
            'friend_price' => ['required', 'boolean'],
            'voucher' => [function ($attribute, $value, $fail) {
                if (! $this->voucherService->applyable($value, $this->total_price)) {
                    $fail(__('voucher expired'));
                }
            }],
            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.price' => ['required_if:friend_price,true', 'numeric'],
            'products.*.discount_price' => ['required_if:friend_price,true', 'numeric'],
            'products.*.qty' => ['required', 'numeric', 'min:1', new CheckProductStock],
        ];
    }

    public function store(): Selling
    {
        return $this->sellingService->create($this->all());
    }
}
