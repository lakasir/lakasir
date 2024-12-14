<?php

use App\Livewire\PriceSetting;
use App\Models\Tenants\CartItem;
use App\Models\Tenants\Category;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

it('price setting renders successfully', function () {
    Category::factory()->create();
    $product = Product::factory()
        ->create([
            'name' => 'test',
            'initial_price' => 10000,
            'selling_price' => 20000,
            'stock' => 10,
        ]);
    PriceUnit::factory()->create([
        'product_id' => $product->id,
    ]);

    $cartItem = CartItem::factory()->create([
        'user_id' => User::factory()->create()->id,
        'product_id' => $product,
        'qty' => 1,
    ]);

    Livewire::test(PriceSetting::class)
        ->set('cartItem', $cartItem)
        ->assertStatus(200);
});
