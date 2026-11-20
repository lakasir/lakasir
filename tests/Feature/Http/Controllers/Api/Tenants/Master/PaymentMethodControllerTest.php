<?php

use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Storage;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('payment methods expose a type the POS app can render', function () {
    Storage::fake(config('filesystems.upload_disk'));

    PaymentMethod::create([
        'name' => 'QRIS',
        'is_cash' => false,
        'is_qris' => true,
        'qris_image' => 'qris/merchant.png',
    ]);
    PaymentMethod::create([
        'name' => 'Debit BCA',
        'is_cash' => false,
        'is_debit' => true,
    ]);

    $response = actingAs(User::first())->getJson('/api/master/payment-method')->assertOk();

    $methods = collect($response->json('data'))->keyBy('name');

    expect($methods['Cash']['type'])->toBe('cash')
        ->and($methods['Cash']['qris_image_url'])->toBeNull()
        ->and($methods['QRIS']['type'])->toBe('qr')
        ->and($methods['QRIS']['qris_image_url'])->toBe(Storage::disk(config('filesystems.upload_disk'))->url('qris/merchant.png'))
        ->and($methods['Debit BCA']['type'])->toBe('other');
});

test('a credit method is not treated as cash even when flagged as cash', function () {
    PaymentMethod::create([
        'name' => 'Kasbon',
        'is_cash' => true,
        'is_credit' => true,
    ]);

    $response = actingAs(User::first())->getJson('/api/master/payment-method')->assertOk();

    expect(collect($response->json('data'))->firstWhere('name', 'Kasbon')['type'])->toBe('other');
});
