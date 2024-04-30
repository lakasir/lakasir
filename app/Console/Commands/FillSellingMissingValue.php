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
     */
    public function handle()
    {
        Selling::all()->each(function ($selling) {
            $total_net_price = 0;
            $selling->sellingDetails->each(function ($sellingDetail) use (&$total_net_price) {
                $net_price = $sellingDetail->product->initial_price * $sellingDetail->qty;
                $sellingDetail->update([
                    'cost' => $net_price,
                ]);
                $total_net_price += $net_price;
            });
            $selling->update([
                'total_cost' => $total_net_price,
            ]);
        });
    }
}
