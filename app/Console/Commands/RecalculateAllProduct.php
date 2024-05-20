<?php

namespace App\Console\Commands;

use App\Events\RecalculateEvent;
use App\Models\Tenants\Product;
use Illuminate\Console\Command;

class RecalculateAllProduct extends Command
{
    protected $signature = 'app:recalculate-all-product';

    public function handle()
    {
        RecalculateEvent::dispatch(Product::all(), []);
    }
}
