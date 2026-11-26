<?php

namespace App\Http\Requests\Tenants\Sellings;

use App\Models\Tenants\Selling;
use App\Services\Tenants\CheckoutProducts;
use App\Services\Tenants\SellingService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

/**
 * The API checkout. Its rules and total checks live in SellingService
 * (rules() and validateTotals()), which the web cashier uses as well.
 */
class TransactionSellingStoreRequest extends FormRequest
{
    /** Server-computed totals, set once the input has a valid shape. */
    private ?array $totals = null;

    public function __construct(private SellingService $sellingService) {}

    public function authorize(): bool
    {
        $this->sellingService->ensureCashDrawerIsOpen();

        if ($this->boolean('friend_price') && ! $this->user()?->can('use friend price')) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        // Load the cart's products before the stock rule looks at them: the
        // whole cart in one query instead of one query per line.
        app(CheckoutProducts::class)->loadProducts($this->input('products', []));

        return $this->sellingService->rules($this->all());
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->totals = $this->sellingService->validateTotals($validator, $this->all());
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
