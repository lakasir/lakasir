<?php

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use App\Services\Tenants\StockService;
use Illuminate\Support\Facades\Cache;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Setting::set('selling_method', 'fifo');
    Cache::clear();

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

describe('StockService addStock (Bug #16)', function () {
    test('addStock increases stock on the last entry when stock entry exists', function () {
        $stock = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 5,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        $stockService = new StockService();
        $stockService->addStock($this->product, 3);

        expect($stock->fresh()->stock)->toBe(8.0);
    });

    test('addStock adds to product stock when no ready stock entry exists', function () {
        $this->product->stock = 0;
        $this->product->save();

        $stockService = new StockService();
        $stockService->addStock($this->product, 10);

        expect($this->product->fresh()->stock)->toBe(10.0);
    });

    test('addStock correctly adds to entry even when entry has less stock than qty added', function () {
        // This is the Bug #16 regression test:
        // Previously addStock would call reduceStock when stock < qty,
        // which DECREASED total stock instead of increasing it.
        $stock = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 2,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        $stockService = new StockService();
        // Adding 5 to an entry that has 2 should result in 7 (NOT calling reduceStock)
        $stockService->addStock($this->product, 5);

        expect($stock->fresh()->stock)->toBe(7.0);
    });

    test('addStock adds large quantity correctly', function () {
        $stock = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 3,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        $stockService = new StockService();
        $stockService->addStock($this->product, 100);

        expect($stock->fresh()->stock)->toBe(103.0);
    });

    test('addStock never reduces total available stock (Bug #16 regression)', function () {
        $stock = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 5,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        $totalBefore = Stock::where('product_id', $this->product->id)
            ->where('is_ready', true)
            ->where('type', 'in')
            ->sum('stock');

        $stockService = new StockService();
        $stockService->addStock($this->product, 10);

        $totalAfter = Stock::where('product_id', $this->product->id)
            ->where('is_ready', true)
            ->where('type', 'in')
            ->sum('stock');

        // Total stock should have INCREASED by 10, not decreased
        expect($totalAfter)->toBe($totalBefore + 10);
    });

    test('addStock with multiple calls accumulates correctly', function () {
        $stock = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 5,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        $stockService = new StockService();
        $stockService->addStock($this->product, 3);
        $stockService->addStock($this->product, 7);

        expect($stock->fresh()->stock)->toBe(15.0);
    });
});

describe('StockService reduceStock', function () {
    test('reduceStock reduces stock on the last entry', function () {
        $stock = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        $stockService = new StockService();
        $stockService->reduceStock($this->product, 3);

        expect($stock->fresh()->stock)->toBe(7.0);
    });

    test('reduceStock reduces product stock when no entries exist', function () {
        $this->product->stock = 15;
        $this->product->save();

        $stockService = new StockService();
        $stockService->reduceStock($this->product, 5);

        expect($this->product->fresh()->stock)->toBe(10.0);
    });
});