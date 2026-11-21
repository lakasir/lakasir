<?php

use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabaseWithTenant::class);

$reports = [
    '/member/purchasing-report/generate' => 'generate purchasing report',
    '/member/selling-report/generate' => 'generate selling report',
    '/member/product-report/generate' => 'generate product report',
    '/member/cashier-report/generate' => 'generate cashier report',
];

test('a guest is sent to the shop login instead of getting a report', function () use ($reports) {
    foreach ($reports as $url => $permission) {
        get($url)->assertRedirect('/member/login');
    }
});

test('a cashier without the report permission gets 403', function () use ($reports) {
    $user = User::create([
        'email' => 'cashier@mail.com',
        'password' => bcrypt('password'),
    ]);

    foreach ($reports as $url => $permission) {
        actingAs($user)->get($url)->assertForbidden();
    }
});

test('the owner can download every report', function () use ($reports) {
    $owner = User::first();
    // The tenant panel's login stores the time zone the reports format with.
    $owner->profile()->updateOrCreate(['user_id' => $owner->id], ['timezone' => 'Asia/Jakarta']);

    foreach ($reports as $url => $permission) {
        expect($owner->can($permission))->toBeTrue();

        actingAs($owner)
            ->get($url.'?start_date=2026-01-01&end_date=2026-01-31')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
});

test('the unused receipt demo route is gone', function () {
    actingAs(User::first())->get('/member/sellings/1/print')->assertNotFound();
});
