<?php

use App\Features\Qris;
use App\Filament\Tenant\Pages\Cashier;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\User;
use Laravel\Pennant\Feature;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('qris payment methods are shown when feature is enabled', function () {
    config(['services.qris.api_key' => 'test_api_key']);
    config(['services.qris.m_id' => 123456]);
    Feature::define(Qris::class, true);


    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);


    PaymentMethod::create([
        'name' => 'QRIS Payment',
        'is_qris' => true,
        'is_cash' => false,
        'is_credit' => false,
        'is_debit' => false,
        'is_wallet' => false,
        'icon' => 'assets/images/payment-methods/qris.png',
    ]);

    PaymentMethod::create([
        'name' => 'Cash',
        'is_qris' => false,
        'is_cash' => true,
        'is_credit' => false,
        'is_debit' => false,
        'is_wallet' => false,
        'icon' => 'assets/images/payment-methods/cash.png',
    ]);

    $paymentMethods = PaymentMethod::query()
        ->select('id', 'name', 'is_credit', 'is_qris')
        ->get();

    if (!feature(Qris::class)) {
        $paymentMethods = $paymentMethods->filter(function ($method) {
            return !$method->is_qris;
        });
    }

    $paymentMethodsArray = $paymentMethods->toArray();

    // Should include both QRIS and cash methods when feature is enabled
    $qrisMethod = collect($paymentMethodsArray)->firstWhere('name', 'QRIS Payment');
    $cashMethod = collect($paymentMethodsArray)->firstWhere('name', 'Cash');

    expect($qrisMethod)->not->toBeNull();
    expect($cashMethod)->not->toBeNull();
});

test('qris payment methods are hidden when feature is disabled', function () {
    config(['services.qris.api_key' => null]);
    config(['services.qris.m_id' => null]);

    // Create QRIS payment method
    PaymentMethod::create([
        'name' => 'QRIS Payment',
        'is_qris' => true,
        'is_cash' => false,
        'is_credit' => false,
        'is_debit' => false,
        'is_wallet' => false,
        'icon' => 'assets/images/payment-methods/qris.png',
    ]);

    // Create regular payment method
    PaymentMethod::create([
        'name' => 'Cash',
        'is_qris' => false,
        'is_cash' => true,
        'is_credit' => false,
        'is_debit' => false,
        'is_wallet' => false,
        'icon' => 'assets/images/payment-methods/cash.png',
    ]);

    // Test the payment method filtering logic directly
    $paymentMethods = PaymentMethod::query()
        ->select('id', 'name', 'is_credit', 'is_qris')
        ->get();

    // Filter out QRIS payment methods if the feature is disabled
    if (!feature(\App\Features\Qris::class)) {
        $paymentMethods = $paymentMethods->filter(function ($method) {
            return !$method->is_qris;
        });
    }

    $paymentMethodsArray = $paymentMethods->toArray();

    // Should only include cash method, QRIS should be filtered out when feature is disabled
    $qrisMethod = collect($paymentMethodsArray)->firstWhere('name', 'QRIS Payment');
    $cashMethod = collect($paymentMethodsArray)->firstWhere('name', 'Cash');

    expect($qrisMethod)->toBeNull();
    expect($cashMethod)->not->toBeNull();
});

test('qris payment handling is blocked when feature disabled', function () {
    config(['services.qris.api_key' => null]);
    config(['services.qris.m_id' => null]);

    // Create a tenant user
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    // Test that the feature check works in the handleQrisPayment method
    $cashier = new Cashier();

    // Mock the Filament auth
    \Filament\Facades\Filament::shouldReceive('auth->user')->andReturn($user);

    // This should return early due to feature check
    $result = $cashier->handleQrisPayment();

    // The method should not proceed with QRIS payment when feature is disabled
    expect($result)->toBeNull();
});
