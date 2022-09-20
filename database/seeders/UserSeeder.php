<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@lakasir.com',
        ]);
    }
}
