<?php

use App\Models\Company;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

uses(
    TestCase::class,
)->in('Feature', 'Unit');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/**
 * Create a company and enter it, the way a request would. Call before touching
 * any company-owned model: the global scope filters on the current company.
 */
function mockCompany(string $name = 'Toko Testing'): Company
{
    $company = Company::create(['name' => $name]);

    $company->makeCurrent();

    return $company;
}

/**
 * A company plus an owner attached to it, seeded with the permission set.
 */
function mockTenant(string $name = 'toko_testing'): Company
{
    $company = mockCompany($name);

    $user = User::firstOrCreate(
        ['email' => $name.'@mail.com'],
        ['password' => bcrypt('password')],
    );

    $user->companies()->syncWithoutDetaching([$company->id]);

    foreach (['PermissionSeeder', 'PaymentMethodSeeder'] as $seeder) {
        Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
    }

    $user->assignRole('admin');

    return $company;
}
