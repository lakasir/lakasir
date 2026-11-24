<?php

use App\Models\Tenants\About;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('every report still renders after the streaming rewrite', function (string $url) {
    About::create(['shop_name' => 'Toko Budi', 'business_type' => 'retail']);
    $owner = User::first();
    $owner->profile()->updateOrCreate(['user_id' => $owner->id], ['timezone' => 'Asia/Jakarta']);

    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id, 'stock' => 100]);
    $selling = Selling::create([
        'date' => now()->toDateString(),
        'payed_money' => 20000,
        'money_changes' => 0,
        'total_price' => 20000,
        'total_qty' => 2,
        'is_paid' => true,
    ]);
    SellingDetail::create([
        'selling_id' => $selling->id,
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 20000,
        'cost' => 10000,
        'discount_price' => 0,
    ]);

    actingAs($owner)
        ->get($url)
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
})->with([
    '/member/selling-report/generate',
    '/member/product-report/generate',
    '/member/cashier-report/generate',
    '/member/purchasing-report/generate',
]);
