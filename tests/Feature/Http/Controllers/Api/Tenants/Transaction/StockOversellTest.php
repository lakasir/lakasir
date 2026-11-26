<?php

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Stock;
use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    $category = Category::factory()->create();
    $this->product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Kopi',
        'initial_price' => 10000,
        'selling_price' => 20000,
        'stock' => 5,
        'is_non_stock' => false,
        'type' => 'product',
    ]);
    Stock::factory()->createQuietly([
        'product_id' => $this->product->id,
        'stock' => 5,
        'initial_price' => 10000,
        'selling_price' => 20000,
        'type' => 'in',
        'is_ready' => true,
        'date' => now()->format('Y-m-d'),
    ]);
});

test('two lines of the same product cannot exceed the stock together', function () {
    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 200000,
        'friend_price' => false,
        'expected_total' => 200000,
        'products' => [
            ['product_id' => $this->product->id, 'qty' => 3],
            ['product_id' => $this->product->id, 'qty' => 3],
        ],
    ])->assertUnprocessable()->assertJsonValidationErrors(['products.0.qty', 'products.1.qty']);
});

test('a sale that fits the stock still goes through', function () {
    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 100000,
        'friend_price' => false,
        'expected_total' => 100000,
        'products' => [
            ['product_id' => $this->product->id, 'qty' => 3],
            ['product_id' => $this->product->id, 'qty' => 2],
        ],
    ])->assertOk();
});

test('a non-stock product sells whatever its stock number says', function () {
    $service = Product::factory()->create([
        'name' => 'Jasa Bungkus',
        'initial_price' => 0,
        'selling_price' => 5000,
        'stock' => 0,
        'is_non_stock' => true,
        'type' => 'product',
    ]);

    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 10000,
        'friend_price' => false,
        'expected_total' => 10000,
        'products' => [['product_id' => $service->id, 'qty' => 2]],
    ])->assertOk();
});
