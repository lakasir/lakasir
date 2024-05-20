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
        $products->each(function (Product $product) {
            $product->stock = $product->stock_calculate;
            $product->initial_price = $product->initial_price_calculate;
            $product->selling_price = $product->selling_price_calculate;
            $product->save();
        });
    }
}
