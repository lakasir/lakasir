<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

uses(
    Tests\TestCase::class,
    // Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function mockTenant(): Tenant
{
    DB::statement('DROP DATABASE IF EXISTS lakasir_tenancy_toko_test');
    $data = [
        'name' => 'toko_test',
        'domain' => 'toko_test.'.config('tenancy.central_domains')[0],
        'email' => 'toko_test@mail.com',
        'password' => 'password',
        'full_name' => 'Toko Test',
        'shop_name' => 'Toko Test',
        'business_type' => 'Retail',
    ];
    $tenant = Tenant::create([
        'id' => 'toko_test',
        'tenancy_db_name' => 'lakasir_tenancy_toko_test',
    ]);
    $tenant->domains()->create([
        'domain' => $data['domain'],
    ]);
    $tenant->user()->create([
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    $tenant->user->about()->create([
        'shop_name' => $data['shop_name'],
        'business_type' => $data['business_type'],
    ]);

    Artisan::call('tenants:seed', [
        '--tenants' => [$tenant->id],
        '--force' => true,
    ]);

    return $tenant;
}
