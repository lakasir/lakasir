<?php

namespace App\Console\Commands;

use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use App\Notifications\StockRunsOut;
use Illuminate\Console\Command;

class FCM extends Command
{
    protected $signature = 'app:fcm';

    public function handle(): void
    {
        $this->sentTheStockAlert();
    }

    private function sentTheStockAlert(): void
    {
        $minStockSetting = Setting::get('minimum_stock_nofication', 0);
        $runsOutStock = Product::select('id', 'name', 'stock')
            ->where('type', 'product')
            ->where('is_non_stock', false)
            ->where('show', 1)
            ->get()
            ->filter(function (Product $product) use ($minStockSetting) {
                return $product->stock <= $minStockSetting;
            })
            ->values();

        if ($runsOutStock->count() > 0) {
            $users = User::get();
            foreach ($users as $user) {
                $user->notify(new StockRunsOut($runsOutStock->toArray()));
            }
        }
    }
}
