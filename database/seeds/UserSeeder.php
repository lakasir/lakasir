<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        factory(User::class)->create([
            'email' => 'admin@lakasir.deb',
            'username' => 'admin'
        ]);
        factory(User::class, 10)->create();
    }
}
