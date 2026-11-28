<?php

use App\Models\Company;
use App\Models\Tenants\Supplier;
use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

/**
 * A cashier: a member of the shop, signed in, with no permissions. Membership
 * matters: the API refuses a user who belongs to no shop before any permission
 * check runs.
 */
function cashier(): User
{
    $cashier = User::create([
        'email' => 'cashier@mail.com',
        'password' => bcrypt('password'),
    ]);

    $cashier->companies()->attach(Company::currentId());

    return $cashier;
}

test('suppliers need a supplier permission', function () {
    $user = cashier();
    $supplier = Supplier::create(['name' => 'PT Sumber']);

    actingAs($user)->getJson('/api/master/supplier')->assertForbidden();
    actingAs($user)->getJson("/api/master/supplier/{$supplier->id}")->assertForbidden();
    actingAs($user)->postJson('/api/master/supplier', ['name' => 'Baru'])->assertForbidden();
    actingAs($user)->putJson("/api/master/supplier/{$supplier->id}", ['name' => 'Baru'])->assertForbidden();
    actingAs($user)->deleteJson("/api/master/supplier/{$supplier->id}")->assertForbidden();

    $owner = User::first();
    actingAs($owner)->getJson('/api/master/supplier')->assertOk();
    actingAs($owner)->deleteJson("/api/master/supplier/{$supplier->id}")->assertOk();
});

test('settings need a setting permission', function () {
    $user = cashier();
    $owner = User::first();

    actingAs($user)->getJson('/api/setting/all')->assertForbidden();
    actingAs($user)->postJson('/api/setting', ['key' => 'default_tax', 'value' => 11])->assertForbidden();

    actingAs($owner)->getJson('/api/setting/all')->assertOk();
    actingAs($owner)->postJson('/api/setting', ['key' => 'default_tax', 'value' => 11])->assertOk();
});

test('the dashboard totals need a revenue permission', function () {
    $user = cashier();
    $owner = User::first();

    // The time zone comes from the cashier's profile on the web app.
    $owner->profile()->updateOrCreate(['user_id' => $owner->id], ['timezone' => 'Asia/Jakarta']);

    foreach (['total-revenue', 'total-gross-profit', 'total-sales'] as $metric) {
        actingAs($user)->getJson("/api/transaction/dashboard/{$metric}")->assertForbidden();
        actingAs($owner)->getJson("/api/transaction/dashboard/{$metric}?timezone=Asia/Jakarta")->assertOk();
    }
});

test('about is readable by anyone signed in but only updatable with the permission', function () {
    $user = cashier();
    $owner = User::first();

    actingAs($user)->getJson('/api/about')->assertOk();
    actingAs($user)
        ->putJson('/api/about', ['business_type' => 'retail', 'shop_name' => 'Curang'])
        ->assertForbidden();

    actingAs($owner)
        ->putJson('/api/about', ['business_type' => 'retail', 'shop_name' => 'Toko Testing'])
        ->assertOk();
});
