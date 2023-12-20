<?php

namespace Database\Seeders;

use App\Models\Tenants\User;
use App\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        // get database connection
        $dbName = DB::connection()->getDatabaseName();
        $tenant = Tenant::find(Str::after($dbName, 'lakasir_'));
        User::truncate();
        User::factory()->create([
            'name' => $tenant->tenancy_db_profile_full_name,
            'email' => $tenant->tenancy_db_profile_email,
            'password' => $tenant->tenancy_db_profile_password,
        ]);
    }
}
