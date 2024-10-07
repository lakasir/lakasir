<?php

namespace App\Observers;

use App\Features\ProductStock;
use App\Models\Tenants\Product;
use App\Services\Tenants\StockService;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Str;
use Laravel\Pennant\Feature;

class ProductObserver extends AbstractObserver implements DataAwareRule
{
    protected $data = [];

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    private function generateSku(Product $product): string
    {
        $prefix = Str::of($product->category->name)->substr(0, 3)->upper();

        return $prefix.'-'.Str::of($product->name)->substr(0, 3)->upper().'-'.Str::of($product->id)->padLeft(4, 0)->value();
    }

    private function generateBarcode(Product $product): string
    {
        $prefix = Str::of($product->category->name)->substr(0, 3)->upper();

        return $prefix.'-'.Str::of($product->name)->substr(0, 3)->upper().'-'.Str::of($product->id)->padLeft(4, 0)->value();
    }

    public function creating(Product $product): void
    {
        if ($product->expired != null) {
            $this->data = [
                'expired' => $product->expired,
            ];
            $product->expired = null;
        }
    }

    public function created(Product $product)
    {
        if (! Feature::active(ProductStock::class)) {
            $product->is_non_stock = true;
        }
        if (! $product->sku) {
            $product->sku = $this->generateSku($product);
        }
        if (! $product->barcode) {
            $product->barcode = $this->generateBarcode($product);
        }
        $product->save();
        $stockService = new StockService();
        $stockService->create([
            'product_id' => $product->getKey(),
            'stock' => $product->stock ?? 0,
            'init_stock' => $product->stock ?? 0,
            'initial_price' => $product->initial_price ?? 0,
            'selling_price' => $product->selling_price,
            'type' => 'in',
            'expired' => $this->data['expired'] ?? null,
            'is_ready' => true,
        ]);
    }
}
