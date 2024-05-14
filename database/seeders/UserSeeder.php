<?php

namespace Database\Seeders;

use App\Models\Tenants\User;
use App\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = 'superadmin@admin.com';
        $password = bcrypt('password');
        if (Schema::hasTable('tenant')) {
            $dbName = DB::connection()->getDatabaseName();
            $tenant = Tenant::find(Str::after($dbName, 'lakasir_')) ?? tenant();
            $email = $tenant->user->email;
            $password = $tenant->user->password;
        }
        User::create([
            'email' => $email,
            'password' => $password,
        ]);
    }
}
