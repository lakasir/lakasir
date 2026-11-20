<?php

namespace App\Http\Requests\Tenants\Sellings;

use App\Features\Discount;
use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Rules\CheckProductStock;
use App\Services\Tenants\SellingService;
use App\Services\VoucherService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Laravel\Pennant\Feature;

class TransactionSellingStoreRequest extends FormRequest
{
    /** Server-computed totals, set once the input has a valid shape. */
    private ?array $totals = null;

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

        if ($this->boolean('friend_price') && ! $this->user()?->can('use friend price')) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'fee' => ['numeric'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'payed_money' => ['nullable', 'numeric', 'min:0'],
            'friend_price' => ['required', 'boolean'],
            'total_price' => ['required_if:friend_price,true', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'required_if:friend_price,true'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'voucher' => ['nullable', 'string'],
            'expected_total' => ['nullable', 'numeric'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.qty' => ['required', 'numeric', 'min:1', new CheckProductStock],
            'products.*.discount_price' => ['nullable', 'numeric', 'min:0'],
            'products.*.price_unit_id' => ['nullable', 'exists:price_units,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $this->validatePriceUnits($validator);
            $this->validateDiscountsAllowed($validator);
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $data = $this->all();
            $totals = $this->sellingService->calculate($data);
            $this->totals = $totals;

            foreach ($totals['products'] as $i => $line) {
                if ($line['discount_price'] > $line['price']) {
                    $validator->errors()->add("products.$i.discount_price", __('The discount cannot be more than the line price.'));
                }
            }

            if (! empty($data['voucher']) && ! $totals['voucher_applied']) {
                $validator->errors()->add('voucher', __('voucher expired'));
            }

            if ($totals['total_discount_per_item'] + ($totals['discount_price'] ?? 0) > $totals['subtotal'] && ! $totals['friend_price']) {
                $validator->errors()->add('discount_price', __('The discount cannot be more than the subtotal.'));
            }

            if ($totals['friend_price']) {
                $this->validateFriendPrice($validator, $totals);
            }

            $paymentMethod = PaymentMethod::find($totals['payment_method_id']);
            if (! $paymentMethod?->is_credit && $totals['payed_money'] < $totals['amount_due']) {
                $validator->errors()->add('payed_money', __('validation.gte.numeric', [
                    'attribute' => 'payed money',
                    'value' => $totals['amount_due'],
                ]));
            }

            if ($this->filled('expected_total') && abs((float) $this->input('expected_total') - $totals['amount_due']) > 0.009) {
                $validator->errors()->add('expected_total', __('The total does not match the server total.'));
            }
        });
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->totals === null) {
            parent::failedValidation($validator);
        }

        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()->toArray(),
            'totals' => $this->publicTotals($this->totals),
        ], 422));
    }

    public function store(): Selling
    {
        return $this->sellingService->create(array_merge($this->all(), $this->totals ?? $this->sellingService->calculate($this->all())));
    }

    private function validatePriceUnits(Validator $validator): void
    {
        foreach ($this->input('products', []) as $i => $line) {
            if (empty($line['price_unit_id'])) {
                continue;
            }

            $belongs = PriceUnit::whereKey($line['price_unit_id'])
                ->where('product_id', $line['product_id'])
                ->exists();

            if (! $belongs) {
                $validator->errors()->add("products.$i.price_unit_id", __('The price unit does not belong to this product.'));
            }
        }
    }

    private function validateDiscountsAllowed(Validator $validator): void
    {
        if (Feature::active(Discount::class)) {
            return;
        }

        if ((float) $this->input('discount_price', 0) > 0) {
            $validator->errors()->add('discount_price', __('Discounts are turned off for this store.'));
        }

        foreach ($this->input('products', []) as $i => $line) {
            if ((float) ($line['discount_price'] ?? 0) > 0) {
                $validator->errors()->add("products.$i.discount_price", __('Discounts are turned off for this store.'));
            }
        }
    }

    private function validateFriendPrice(Validator $validator, array $totals): void
    {
        $customTotal = (float) $this->input('total_price');

        if ($customTotal > $totals['normal_amount_due']) {
            $validator->errors()->add('total_price', __('A friend price cannot be more than the normal total.'));
        }

        if ($totals['total_cost'] > 0 && $customTotal < $totals['total_cost']) {
            $validator->errors()->add('total_price', __('A friend price cannot be below the cost price.'));
        }
    }

    private function publicTotals(array $totals): array
    {
        return Arr::only($totals, [
            'subtotal',
            'total_discount_per_item',
            'discount_price',
            'tax',
            'tax_price',
            'total_price',
            'amount_due',
            'money_changes',
        ]);
    }
}
