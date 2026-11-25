<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Tenants\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first() ?? Company::create(['name' => 'Lakasir']);

        $company->makeCurrent();

        $user = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'superadmin@admin.com')],
            ['password' => bcrypt(env('ADMIN_PASSWORD', 'password'))],
        );

        $user->companies()->syncWithoutDetaching([$company->id]);
    }
}
