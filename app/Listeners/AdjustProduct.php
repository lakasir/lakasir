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
                // One read of the product's batches feeds the stock total and
                // both prices, instead of three separate lookups.
                $batches = $product->stockLatestCalculateIn()->get();

                $product->stock = (float) $batches->sum('stock');
                $product->initial_price = $batches->first()?->initial_price ?? $product->initial_price;
                $product->selling_price = $batches->first()?->selling_price ?? $product->selling_price;
                $product->save();
            });
        }
    }
}
