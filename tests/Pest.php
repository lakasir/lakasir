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

use App\Services\RegisterTenant;
use App\Tenant;
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
    DB::statement('DROP DATABASE IF EXISTS lakasir_toko_testing');
    Tenant::where('id', 'toko_testing')->delete();
    $data = [
        'name' => 'toko_testing',
        'domain' => 'toko_testing.'.config('tenancy.central_domains')[0],
        'email' => 'toko_testing@mail.com',
        'password' => 'password',
        'full_name' => 'Toko Testing',
        'shop_name' => 'Toko Testing',
        'business_type' => 'Retail',
    ];
    $sRegisterTenant = new RegisterTenant();
    $tenant = $sRegisterTenant->create($data);

    return $tenant;
}
