<?php

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Observers\ProductObserver;
use App\Services\Tenants\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('the observer keeps no static state between products', function () {
    $statics = array_map(
        fn (ReflectionProperty $p) => $p->getName(),
        (new ReflectionClass(ProductObserver::class))->getProperties(ReflectionProperty::IS_STATIC),
    );

    expect($statics)->not->toContain('tempBarcodesData');
});

test('a product whose category is missing still gets a sku prefix', function () {
    $product = Product::create([
        'name' => 'Tanpa Kategori',
        'category_id' => Category::factory()->create()->id,
        'initial_price' => 1000,
        'selling_price' => 2000,
        'stock' => 1,
        'type' => 'product',
    ]);

    // A category row can be gone (soft-deleted, or a legacy product): the sku
    // prefix must not dereference null.
    $product->setRelation('category', null);
    $prefix = (new ReflectionMethod(ProductObserver::class, 'prefix'))->invoke(new ProductObserver, $product);

    expect($prefix)->toBe('GEN-TAN-')
        ->and($product->fresh()->sku)->not->toBeNull()
        ->and($product->fresh()->barcodes()->primary()->active()->exists())->toBeTrue();
});

test('the product service stores the barcode it is given, and only that one', function () {
    $category = Category::factory()->create();

    $product = app(ProductService::class)->create([
        'name' => 'Kopi Susu',
        'category' => $category->id,
        'sku' => 'KOP-001',
        'barcode' => '8990001',
        'initial_price' => 5000,
        'selling_price' => 10000,
        'stock' => 3,
        'type' => 'product',
        'is_non_stock' => false,
    ]);

    // One saleable barcode, not the generated one plus the caller's.
    expect($product->barcodes()->pluck('code')->all())->toBe(['8990001'])
        ->and($product->category_id)->toBe($category->id);
});

test('creating a product with a sku does not rewrite the row', function () {
    $category = Category::factory()->create();

    DB::enableQueryLog();
    app(ProductService::class)->create([
        'name' => 'Teh',
        'category' => $category->id,
        'sku' => 'TEH-001',
        'initial_price' => 1000,
        'selling_price' => 2000,
        'stock' => 1,
        'type' => 'product',
        'is_non_stock' => false,
    ]);
    $log = DB::getQueryLog();
    DB::disableQueryLog();

    $productsUpdates = array_filter(
        $log,
        fn (array $q) => str_starts_with($q['query'], 'update `products`'),
    );

    // The observer used to call save() again after the insert.
    expect($productsUpdates)->toBe([]);
});

test('the barcode description is translated, not hardcoded Spanish', function () {
    $category = Category::factory()->create();
    $product = app(ProductService::class)->create([
        'name' => 'Susu',
        'category' => $category->id,
        'barcode' => '8990002',
        'initial_price' => 1000,
        'selling_price' => 2000,
        'stock' => 1,
        'type' => 'product',
        'is_non_stock' => false,
    ]);

    expect($product->barcodes()->first()->description)->toBe(__('Auto-generated code'));
});

test('deleting a product removes its images from the disk', function () {
    Storage::fake(config('filesystems.upload_disk'));
    $category = Category::factory()->create();
    $product = app(ProductService::class)->create([
        'name' => 'Roti',
        'category' => $category->id,
        'initial_price' => 1000,
        'selling_price' => 2000,
        'stock' => 1,
        'type' => 'product',
        'is_non_stock' => false,
    ]);

    Storage::disk(config('filesystems.upload_disk'))->put('product/roti.png', 'x');
    DB::table('product_images')->insert([
        'product_id' => $product->id,
        'name' => 'product/roti.png',
        'url' => 'http://toko.test/product/roti.png',
        'size' => '1',
        'type' => 'png',
    ]);

    app(ProductService::class)->delete($product);

    expect(Product::find($product->id))->toBeNull()
        ->and(Storage::disk(config('filesystems.upload_disk'))->exists('product/roti.png'))->toBeFalse();
});
