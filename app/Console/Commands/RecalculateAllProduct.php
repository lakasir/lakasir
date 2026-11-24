<?php

namespace App\Console\Commands;

use App\Events\RecalculateEvent;
use App\Models\Tenants\Product;
use Illuminate\Console\Command;

class RecalculateAllProduct extends Command
{
    protected $signature = 'app:recalculate-all-product';

    /**
     * Recalculate every product's stock and prices a chunk at a time, so a shop
     * with a large catalog does not load the whole products table into memory.
     */
    public function handle()
    {
        Product::query()->chunkById(200, function ($products) {
            RecalculateEvent::dispatch($products, []);
        });
    }
}
