<?php

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('a product with no photo gets a placeholder that needs no server route', function () {
    $category = Category::factory()->create();

    $product = Product::create([
        'name' => 'Tanpa Foto',
        'category_id' => $category->id,
        'initial_price' => 1000,
        'selling_price' => 2000,
        'stock' => 1,
        'type' => 'product',
    ]);

    // Under a tenant the asset root points at /tenancy/assets, which this app
    // does not serve. The placeholder carries its own pixels instead.
    expect($product->hero_image)->toStartWith('data:image/svg+xml');
});
