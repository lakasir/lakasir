<?php

namespace App\Observers;

use App\Models\Tenants\Product;
use App\Services\Tenants\StockService;
use Illuminate\Support\Str;

class ProductObserver
{
    private function generateSku(Product $product)
    {
        $prefix = Str::of($product->category->name)->substr(0, 3)->upper();
        $product->sku = $prefix.'-'.Str::of($product->name)->substr(0, 3)->upper().'-'.Str::of($product->id)->padLeft(4, 0)->value();
        $product->save();
    }

    public function created(Product $product)
    {
        if (! $product->sku) {
            $this->generateSku($product);
        }
        $stockService = new StockService();
        $stockService->create([
            'product_id' => $product->getKey(),
            'stock' => $product->stock,
            'init_stock' => $product->stock,
            'initial_price' => $product->initial_price,
            'selling_price' => $product->selling_price,
            'type' => 'in',
            'date' => $product->created_at,
        ]);
    }
}
