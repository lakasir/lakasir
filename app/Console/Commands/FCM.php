<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Notifications\StockRunsOut;
use Illuminate\Console\Command;

class FCM extends Command
{
    protected $signature = 'app:fcm';

    public function handle(): void
    {
        Company::where('enabled', true)->each(function (Company $company) {
            $company->makeCurrent();

            $this->sentTheStockAlert($company);
        });

        Company::forgetCurrent();
    }

    private function sentTheStockAlert(Company $company): void
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
            foreach ($company->users as $user) {
                $user->notify(new StockRunsOut($runsOutStock->toArray()));
            }
        }
    }
}
