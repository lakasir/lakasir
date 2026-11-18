<?php

use App\Events\RecalculateEvent;
use App\Listeners\AdjustProduct;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use Illuminate\Support\Collection;
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

describe('AdjustProduct handles Collection correctly (Bug #11)', function () {
    test('AdjustProduct recalculates stock for a single product passed as Collection', function () {
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 15,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        expect($this->product->fresh()->stock)->toBe(0.0);

        RecalculateEvent::dispatch(collect([$this->product]), []);

        expect($this->product->fresh()->stock)->toBe(15.0);
    });

    test('AdjustProduct recalculates stock for multiple products in Collection', function () {
        $product2 = Product::factory()->create([
            'category_id' => $this->product->category_id,
            'name' => 'Test Product 2',
            'initial_price' => 5000,
            'selling_price' => 10000,
            'stock' => 0,
            'is_non_stock' => false,
            'type' => 'product',
            'show' => true,
        ]);

        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        Stock::factory()->createQuietly([
            'product_id' => $product2->id,
            'stock' => 20,
            'initial_price' => 5000,
            'selling_price' => 10000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        RecalculateEvent::dispatch(collect([$this->product, $product2]), []);

        expect($this->product->fresh()->stock)->toBe(10.0);
        expect($product2->fresh()->stock)->toBe(20.0);
    });

    test('AdjustProduct defensively handles a single Model instead of Collection (Bug #11 regression)', function () {
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 25,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        // Create a second product that should NOT be affected
        $product2 = Product::factory()->create([
            'category_id' => $this->product->category_id,
            'name' => 'Unaffected Product',
            'initial_price' => 5000,
            'selling_price' => 10000,
            'stock' => 999,
            'is_non_stock' => false,
            'type' => 'product',
            'show' => true,
        ]);

        // Pass a single Model (the old bug behavior)
        RecalculateEvent::dispatch($this->product, []);

        // Only $this->product should be recalculated, NOT all products
        expect($this->product->fresh()->stock)->toBe(25.0);
        expect($product2->fresh()->stock)->toBe(999.0);
    });

    test('AdjustProduct updates selling_price and initial_price', function () {
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'initial_price' => 8000,
            'selling_price' => 15000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        RecalculateEvent::dispatch(collect([$this->product]), []);

        $fresh = $this->product->fresh();
        expect($fresh->stock)->toBe(10.0);
        expect($fresh->initial_price)->toBe(8000.0);
        expect($fresh->selling_price)->toBe(15000.0);
    });
});

describe('RecalculateEvent type safety (Bug #11)', function () {
    test('RecalculateEvent accepts Collection', function () {
        $event = new RecalculateEvent(collect([$this->product]), []);
        expect($event->products)->toBeInstanceOf(Collection::class);
        expect($event->products)->toHaveCount(1);
    });

    test('RecalculateEvent accepts Product', function () {
        $event = new RecalculateEvent($this->product, []);
        expect($event->products)->toBeInstanceOf(Product::class);
    });

    test('AdjustProduct normalizes Model to Collection and recalculates correctly', function () {
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 30,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        $listener = new AdjustProduct();
        $event = new RecalculateEvent($this->product, ['some' => 'data']);

        $listener->handle($event);

        expect($this->product->fresh()->stock)->toBe(30.0);
    });
});