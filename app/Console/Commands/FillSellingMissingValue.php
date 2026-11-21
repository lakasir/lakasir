<?php

namespace App\Console\Commands;

use App\Models\Tenants\Selling;
use Illuminate\Console\Command;

class FillSellingMissingValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-selling-missing-value';

    /**
     * Execute the console command.
     *
     * Pages through the sales so a shop with years of history does not load
     * every sale, its details and its products into memory at once.
     */
    public function handle()
    {
        Selling::query()
            ->with(['sellingDetails.product:id,initial_price'])
            ->chunkById(200, function ($sellings) {
                foreach ($sellings as $selling) {
                    $totalNetPrice = 0;
                    foreach ($selling->sellingDetails as $sellingDetail) {
                        $netPrice = $sellingDetail->product->initial_price * $sellingDetail->qty;
                        $sellingDetail->update(['cost' => $netPrice]);
                        $totalNetPrice += $netPrice;
                    }
                    $selling->update(['total_cost' => $totalNetPrice]);
                }
            });
    }
}
