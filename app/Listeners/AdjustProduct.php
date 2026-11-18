<?php

namespace App\Listeners;

use App\Events\RecalculateEvent;
use App\Models\Tenants\Product;

class AdjustProduct
{
    public function __construct()
    {
    }

    public function handle(RecalculateEvent $event): void
    {
        $products = $event->products;

        if ($products instanceof Product) {
            $products = collect([$products]);
        }

        if ($products->count() > 0) {
            $products->each(function (Product $product) {
                $product->stock = $product->stock_calculate;
                $product->initial_price = $product->initial_price_calculate ?? $product->initial_price;
                $product->selling_price = $product->selling_price_calculate ?? $product->selling_price;
                $product->save();
            });
        }
    }
}
