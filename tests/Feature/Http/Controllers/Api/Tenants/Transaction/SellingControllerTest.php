<?php

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('cashier can create the selling transaction', function () {
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'total_price' => 20000,
        'total_cost' => 10000,
        'total_qty' => 1,
    ]);
});

test('cashier can create the selling transaction with customer number', function () {
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'customer_number' => 123,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'total_price' => 20000,
        'total_cost' => 10000,
        'total_qty' => 1,
        'customer_number' => 123,
    ]);
});

test('cashier can create the selling with tax price', function () {
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 22000,
        'friend_price' => false,
        'tax' => 10,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'total_price' => 22000,
        'tax_price' => 2000,
        'tax' => 10,
    ]);
});

test('cashier cannnot create the selling transaction if the cash drawer enabled but not opened', function () {
    Setting::set('cash_drawer_enabled', true);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('errors.cash_drawer.0', 'Cash drawer is not opened');
});

test('cashier can create the selling transaction if the cash drawer enabled and opened', function () {
    Setting::set('cash_drawer_enabled', true);
    $user = User::first();
    CashDrawer::create([
        'opened_by' => $user->id,
        'cash' => 0,
    ]);

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'cash_drawer_id' => 1,
    ]);
});

beforeEach(function () {
    $product = Product::factory()
        ->createQuietly([
            'name' => 'test',
            'initial_price' => 10000,
            'selling_price' => 20000,
            'stock' => 10,
        ]);

    $this->product = $product;
});
