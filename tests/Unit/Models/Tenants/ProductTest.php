<?php

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use Illuminate\Support\Facades\Cache;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    $category = Category::factory()->create();
    $this->product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Test Product',
        'initial_price' => 10000,
        'selling_price' => 20000,
        'stock' => 0,
        'is_non_stock' => false,
        'type' => 'product',
        'show' => true,
    ]);
});

describe('stock_calculate accessor', function () {
    test('returns 0 when no stock entries exist', function () {
        expect($this->product->fresh()->stock_calculate)->toBe(0);
    });

    test('returns sum of ready in-type entries with stock > 0 for FIFO', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'type' => 'in',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 5,
            'type' => 'in',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        expect($this->product->fresh()->stock_calculate)->toBe(15.0);
    });

    test('excludes out-type entries from stock calculation', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'type' => 'in',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 5,
            'type' => 'out',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        expect($this->product->fresh()->stock_calculate)->toBe(10.0);
    });

    test('excludes entries with stock = 0 for FIFO selling method', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 0,
            'type' => 'in',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'type' => 'in',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        expect($this->product->fresh()->stock_calculate)->toBe(10.0);
    });

    test('excludes entries with is_ready = false', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'type' => 'in',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 20,
            'type' => 'in',
            'is_ready' => false,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        expect($this->product->fresh()->stock_calculate)->toBe(10.0);
    });
});

describe('sellingPriceCalculate accessor (Bug #17)', function () {
    test('returns stock entry selling_price when stock entries exist', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'initial_price' => 12000,
            'selling_price' => 25000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        expect($this->product->fresh()->selling_price_calculate)->toBe(25000.0);
    });

    test('falls back to product selling_price when all stock entries are depleted', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        // Product has selling_price = 20000 from factory
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 0, // depleted entry
            'initial_price' => 8000,
            'selling_price' => 15000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        // FIFO filters out entries with stock = 0, so no entries match
        // Should fall back to product's selling_price (20000), NOT return null
        expect($this->product->fresh()->selling_price_calculate)->toBe(20000.0);
    });

    test('falls back to product selling_price when no stock entries exist at all', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        expect($this->product->fresh()->selling_price_calculate)->toBe(20000.0);
    });
});

describe('initialPriceCalculate accessor (Bug #17)', function () {
    test('returns stock entry initial_price when stock entries exist', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'initial_price' => 8000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        expect($this->product->fresh()->initial_price_calculate)->toBe(8000.0);
    });

    test('falls back to product initial_price when all stock entries are depleted', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        // Product has initial_price = 10000 from factory
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 0, // depleted entry
            'initial_price' => 5000,
            'selling_price' => 15000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        // Should fall back to product's initial_price (10000), NOT return null
        expect($this->product->fresh()->initial_price_calculate)->toBe(10000.0);
    });

    test('falls back to product initial_price when no stock entries exist at all', function () {
        Setting::set('selling_method', 'fifo');
        Cache::clear();

        expect($this->product->fresh()->initial_price_calculate)->toBe(10000.0);
    });
});

describe('scopeInStock (Bug #15)', function () {
    test('includes products with available stock entries', function () {
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'type' => 'in',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        $products = Product::inStock()->where('type', 'product')->get();
        expect($products)->toHaveCount(1);
        expect($products->first()->id)->toBe($this->product->id);
    });

    test('excludes products with no available stock entries', function () {
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 0,
            'type' => 'in',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        $products = Product::inStock()->where('type', 'product')->get();
        expect($products)->toHaveCount(0);
    });

    test('includes non-stock products regardless of stock entries', function () {
        $this->product->update(['is_non_stock' => true]);

        $products = Product::inStock()->where('type', 'product')->get();
        expect($products)->toHaveCount(1);
    });

    test('excludes products with only non-ready stock entries', function () {
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'type' => 'in',
            'is_ready' => false,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        $products = Product::inStock()->where('type', 'product')->get();
        expect($products)->toHaveCount(0);
    });

    test('excludes products with only out-type stock entries', function () {
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'type' => 'out',
            'is_ready' => true,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'date' => now()->format('Y-m-d'),
        ]);

        $products = Product::inStock()->where('type', 'product')->get();
        expect($products)->toHaveCount(0);
    });
});