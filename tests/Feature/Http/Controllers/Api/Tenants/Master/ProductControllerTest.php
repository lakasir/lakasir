<?php

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use Illuminate\Http\Response;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test("can'\t create product", function () {
    $user = User::first();
    actingAs($user)->postJson('/api/master/product', [])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

test('can control pagination size with per_page query', function () {
    $user = User::first();
    $category = Category::factory()->create();
    Product::factory()->count(20)->create([
        'category_id' => $category->id,
    ]);

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product?per_page=5')
        ->assertOk()
        ->assertJsonPath('data.meta.per_page', 5)
        ->assertJsonCount(5, 'data.data');
});

test('can fall back to default pagination when per_page is invalid', function () {
    $user = User::first();
    $category = Category::factory()->create();
    Product::factory()->count(20)->create([
        'category_id' => $category->id,
    ]);

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product?per_page=invalid')
        ->assertOk()
        ->assertJsonPath('data.meta.per_page', Product::query()->getModel()->getPerPage())
        ->assertJsonCount(Product::query()->getModel()->getPerPage(), 'data.data');
});

test('can clamp per_page to maximum of 100', function () {
    $user = User::first();
    $category = Category::factory()->create();
    Product::factory()->count(20)->create([
        'category_id' => $category->id,
    ]);

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product?per_page=999')
        ->assertOk()
        ->assertJsonPath('data.meta.per_page', 100)
        ->assertJsonCount(20, 'data.data');
});

test('store persists barcode to barcode relation', function () {
    $user = User::first();
    $category = Category::factory()->create();

    actingAs($user, 'sanctum')
        ->postJson('/api/master/product', [
            'name' => 'Barcode API Product',
            'sku' => 'SKU-BARCODE-001',
            'barcode' => 'API-BAR-001',
            'category' => $category->id,
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 15000,
            'type' => 'product',
            'is_non_stock' => false,
            'expired' => now()->addDay()->toDateString(),
        ])
        ->assertOk();

    $product = Product::query()->where('name', 'Barcode API Product')->firstOrFail();
    expect($product->barcodes()->primary()->active()->value('code'))->toBe('API-BAR-001');
});

test('update persists changed barcode to barcode relation', function () {
    $user = User::first();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
    ]);

    actingAs($user, 'sanctum')
        ->putJson("/api/master/product/{$product->id}", [
            'barcode' => 'API-BAR-UPDATED',
        ])
        ->assertOk();

    $product->refresh();
    expect($product->barcodes()->primary()->active()->value('code'))->toBe('API-BAR-UPDATED');
});

test('can filter products globally by barcode code', function () {
    $user = User::first();
    $category = Category::factory()->create();

    $productWithBarcode = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Product With Searchable Barcode',
    ]);
    $productWithBarcode->barcodes()->create([
        'code' => 'FILTER-BARCODE-001',
        'type' => 'primary',
        'description' => 'Test barcode',
        'is_active' => true,
    ]);

    Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Another Product',
    ]);

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product?filter[global]=FILTER-BARCODE-001')
        ->assertOk()
        ->assertJsonCount(1, 'data.data')
        ->assertJsonPath('data.data.0.id', $productWithBarcode->id);
});

test('can show product by numeric id', function () {
    $user = User::first();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Product By ID',
    ]);

    actingAs($user, 'sanctum')
        ->getJson("/api/master/product/{$product->id}")
        ->assertOk()
        ->assertJsonPath('data.name', 'Product By ID');
});

test('can show product by barcode', function () {
    $user = User::first();
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Product By Barcode',
    ]);
    $product->barcodes()->create([
        'code' => '8991234567890',
        'type' => 'primary',
        'description' => 'Test barcode',
        'is_active' => true,
    ]);

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product/8991234567890')
        ->assertOk()
        ->assertJsonPath('data.name', 'Product By Barcode');
});

test('can show product by sku', function () {
    $user = User::first();
    $category = Category::factory()->create();
    Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Product By SKU',
        'sku' => 'SKU-MY-PRODUCT-001',
    ]);

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product/SKU-MY-PRODUCT-001')
        ->assertOk()
        ->assertJsonPath('data.name', 'Product By SKU');
});

test('returns 404 for non-existent numeric id', function () {
    $user = User::first();

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product/99999')
        ->assertNotFound();
});

test('returns 404 for non-existent barcode', function () {
    $user = User::first();

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product/NONEXISTENT-BARCODE')
        ->assertNotFound();
});

test('returns 404 for non-existent sku', function () {
    $user = User::first();

    actingAs($user, 'sanctum')
        ->getJson('/api/master/product/NONEXISTENT-SKU')
        ->assertNotFound();
});
