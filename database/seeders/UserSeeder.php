<?php

namespace Database\Seeders;

use App\Constants\Role;
use App\Models\Company;
use App\Models\Tenants\User;
use App\Services\ProvisionCompany;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'superadmin@admin.com')],
            [
                'name' => 'Super Admin',
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
            ],
        );

        $company = Company::first()
            ?? app(ProvisionCompany::class)->create(['name' => 'Lakasir', 'owner' => $user]);

        $company->makeCurrent();
        $company->users()->syncWithoutDetaching([$user->id => ['is_owner' => true]]);

        if (! $user->hasRole(Role::admin)) {
            $user->assignRole(Role::admin);
        }
    }
}
