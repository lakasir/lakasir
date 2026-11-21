<?php

namespace App\Rules;

use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

/**
 * Is the requested quantity still available? Runs once per line, but judges
 * the product as a whole: two lines of the same product are summed, so
 * 3 + 3 of a product with 5 in stock is refused.
 */
class CheckProductStock implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

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
        if ($product->stock < $this->requestedQty($product)) {
            $fail($this->message());
        }
    }

    /**
     * The units of stock every line of this product asks for, in base units
     * (a price unit multiplies the quantity).
     */
    private function requestedQty(Product $product): float
    {
        $qty = 0.0;

        foreach ($this->data['products'] as $line) {
            if ((int) $line['product_id'] !== $product->id) {
                continue;
            }
            $lineQty = (float) $line['qty'];
            $priceUnitId = $line['price_unit_id'] ?? null;
            if ($priceUnitId) {
                $priceUnit = PriceUnit::find($priceUnitId);
                $lineQty *= (float) ($priceUnit?->stock ?? 1);
            }
            $qty += $lineQty;
        }

        return $qty;
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
