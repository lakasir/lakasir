<?php

namespace Database\Seeders;

use App\Models\Tenants\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // truncate() ignores the company scope, so it would empty every shop's
        // categories. Delete only this company's rows.
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        Category::query()->delete();

        Category::create([
            'name' => 'UMUM',
        ]);

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
