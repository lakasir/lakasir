<?php

use App\Constants\VoucherType;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('cashier can check a valid flat voucher before checkout', function () {
    Voucher::factory()->create(['code' => 'HEMAT2K']);

    actingAs(User::first())->postJson('/api/transaction/voucher/check', [
        'code' => 'HEMAT2K',
        'total_price' => 25000,
    ])->assertOk()
        ->assertJsonPath('data.code', 'HEMAT2K')
        ->assertJsonPath('data.discount', 2000)
        ->assertJsonPath('data.total_after_discount', 23000);
});

test('cashier can check a valid percentage voucher', function () {
    Voucher::factory()->create([
        'code' => 'DISKON20',
        'type' => VoucherType::percentage,
        'nominal' => 20,
    ]);

    actingAs(User::first())->postJson('/api/transaction/voucher/check', [
        'code' => 'DISKON20',
        'total_price' => 50000,
    ])->assertOk()
        ->assertJsonPath('data.discount', 10000)
        ->assertJsonPath('data.total_after_discount', 40000);
});

test('checking a voucher does not use up its quota', function () {
    $voucher = Voucher::factory()->create(['kuota' => 3]);

    actingAs(User::first())->postJson('/api/transaction/voucher/check', [
        'code' => $voucher->code,
        'total_price' => 25000,
    ])->assertOk();

    expect($voucher->fresh()->kuota)->toBe(3);
});

test('voucher check explains why a voucher cannot be used', function (array $voucher, float $total, string $reason) {
    Voucher::factory()->create(array_merge(['code' => 'PROMO'], $voucher));

    actingAs(User::first())->postJson('/api/transaction/voucher/check', [
        'code' => 'PROMO',
        'total_price' => $total,
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['code' => $reason]);
})->with([
    'expired' => [['start_date' => now()->subDays(10), 'expired' => now()->subDay()], 25000, 'expired'],
    'not started' => [['start_date' => now()->addDay(), 'expired' => now()->addDays(5)], 25000, 'not active yet'],
    'below minimum' => [['minimal_buying' => 30000], 25000, 'minimum'],
    'quota used up' => [['kuota' => 0], 25000, 'used up'],
]);

test('voucher check rejects an unknown code', function () {
    actingAs(User::first())->postJson('/api/transaction/voucher/check', [
        'code' => 'NOPE',
        'total_price' => 25000,
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['code' => 'not found']);
});

test('voucher check requires code and total price', function () {
    actingAs(User::first())->postJson('/api/transaction/voucher/check', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['code', 'total_price']);
});

test('voucher check needs the create selling permission', function () {
    Voucher::factory()->create(['code' => 'HEMAT2K']);
    $userWithoutRole = User::factory()->create();

    actingAs($userWithoutRole)->postJson('/api/transaction/voucher/check', [
        'code' => 'HEMAT2K',
        'total_price' => 25000,
    ])->assertForbidden();
});
