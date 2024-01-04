<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SellingSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
