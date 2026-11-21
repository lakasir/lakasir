<?php

use App\Http\Requests\Tenants\Master\ProductRequest;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('a product is created through the controller, not the request', function () {
    $category = Category::factory()->create();

    actingAs(User::first())->postJson('/api/master/product', [
        'name' => 'Kopi Susu',
        'category' => $category->id,
        'sku' => 'KOP-001',
        'barcode' => '8990001',
        'initial_price' => 5000,
        'selling_price' => 10000,
        'stock' => 12,
        'type' => 'product',
        'is_non_stock' => false,
    ])->assertOk();

    $product = Product::where('name', 'Kopi Susu')->first();
    expect($product)->not->toBeNull()
        ->and($product->category_id)->toBe($category->id)
        ->and($product->barcodes()->primary()->active()->value('code'))->toBe('8990001');
});

test('the request carries no side effects of its own', function () {
    $methods = get_class_methods(ProductRequest::class);

    // Validation only: the writes moved to ProductService.
    foreach (['created', 'updated', 'deleteImages'] as $gone) {
        expect($methods)->not->toContain($gone);
    }
});

test('an invalid product is a 422 with the field errors', function () {
    actingAs(User::first())->postJson('/api/master/product', [
        'name' => 'X',
        'initial_price' => 20000,
        'selling_price' => 10000,
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'category', 'selling_price']);
});
