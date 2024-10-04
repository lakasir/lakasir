<?php

namespace App\Rules;

use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class CheckProductStock implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * key index from the data
     *
     * @var int
     */
    protected $index;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = Str::of($attribute)->explode('.')[1];
        $dataProduct = $this->data['products'][$index];
        $product = Product::find($dataProduct['product_id']);
        if (! $product) {
            $fail('The product is not found.');

            return;
        }
        if ($product->is_non_stock) {
            return;
        }
        if (isset($dataProduct['price_unit_id']) && $dataProduct['price_unit_id'] != null) {
            $value = PriceUnit::query()->find($dataProduct['price_unit_id'])->stock * $dataProduct['qty'];
        }
        $bool = $product?->stock < $value;

        if ($bool) {
            $fail($this->message());
        }
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The stock of product less than from you request.';
    }
}
