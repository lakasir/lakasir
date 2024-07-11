<?php

namespace Database\Seeders;

use App\Models\Tenants\Product;
use App\Models\Tenants\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class, true);
        Stock::truncate();
        Product::truncate();

        $path = 'database/seeders/retail_product.sql';
        if (file_exists($path)) {
            DB::unprepared(file_get_contents($path));
            $this->command->info('Retail product seeded!');

            return;
        }
        $contactEmail = 'lakasirapp@gmail.com';
        $this->command->warn("Contact the Lakasir owner to get this retail product: mailto://$contactEmail");

    }
}
