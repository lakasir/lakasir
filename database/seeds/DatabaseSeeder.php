<?php

use App\Models\Category;
use App\Models\Item;
use App\Models\Price;
use App\Models\Purchasing;
use App\Models\PurchasingDetail;
use App\Models\SellingDetail;
use App\Models\Selling;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Selling::truncate();
        SellingDetail::truncate();
        Purchasing::truncate();
        PurchasingDetail::truncate();
        Category::truncate();
        Stock::truncate();
        Price::truncate();
        Item::truncate();
        $this->call([
            /* UserSeeder::class, */
            RolesAndPermissionsSeeder::class,
            PaymentMethod::class,
            UnitSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
