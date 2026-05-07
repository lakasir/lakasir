<?php

use App\Events\RecalculateEvent;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use App\Models\Tenants\User;
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

describe('StockController store dispatches RecalculateEvent (Bug #13)', function () {
    test('creating stock via API dispatches RecalculateEvent and updates product stock', function () {
        $user = User::first();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/master/product/{$this->product->id}/stock", [
                'stock' => 15,
                'initial_price' => 10000,
                'selling_price' => 20000,
                'date' => now()->format('Y-m-d'),
            ]);

        $response->assertOk()
            ->assertJsonPath('message', 'success creating stock for ' . $this->product->name);

        // Product stock should be recalculated via RecalculateEvent
        $freshProduct = $this->product->fresh();
        expect($freshProduct->stock)->toBe(15.0);
        expect($freshProduct->initial_price)->toBe(10000.0);
        expect($freshProduct->selling_price)->toBe(20000.0);
    });

    test('creating multiple stock entries via API accumulates stock correctly', function () {
        $user = User::first();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/master/product/{$this->product->id}/stock", [
                'stock' => 10,
                'initial_price' => 10000,
                'selling_price' => 20000,
                'date' => now()->format('Y-m-d'),
            ]);

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/master/product/{$this->product->id}/stock", [
                'stock' => 5,
                'initial_price' => 12000,
                'selling_price' => 22000,
                'date' => now()->format('Y-m-d'),
            ]);

        $freshProduct = $this->product->fresh();
        expect($freshProduct->stock)->toBe(15.0);
    });
});

describe('StockController destroy dispatches RecalculateEvent (Bug #13)', function () {
    test('deleting stock via API dispatches RecalculateEvent and recalculates product stock', function () {
        $user = User::first();

        $stock1 = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        $stock2 = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 5,
            'initial_price' => 12000,
            'selling_price' => 22000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        RecalculateEvent::dispatch(collect([$this->product]), []);
        expect($this->product->fresh()->stock)->toBe(15.0);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/master/product/{$this->product->id}/stock/{$stock1->id}");

        $response->assertOk();

        // Product stock should be recalculated: only 5 from stock2 remains
        $freshProduct = $this->product->fresh();
        expect($freshProduct->stock)->toBe(5.0);
    });

    test('deleting all stock entries sets product stock to 0', function () {
        $user = User::first();

        $stock = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        RecalculateEvent::dispatch(collect([$this->product]), []);
        expect($this->product->fresh()->stock)->toBe(10.0);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/master/product/{$this->product->id}/stock/{$stock->id}");

        $response->assertOk();

        // No stock entries remain — stock should be 0
        expect($this->product->fresh()->stock)->toBe(0.0);
    });
});

describe('Stock replenishment after depletion (core bug scenario)', function () {
    test('product becomes in-stock after stock replenishment through API', function () {
        $user = User::first();

        // Start with 0 stock
        expect($this->product->fresh()->stock)->toBe(0.0);
        expect($this->product->fresh()->stock_calculate)->toBe(0);

        // Add stock via API
        $this->actingAs($user, 'sanctum')
            ->postJson("/api/master/product/{$this->product->id}/stock", [
                'stock' => 10,
                'initial_price' => 10000,
                'selling_price' => 20000,
                'date' => now()->format('Y-m-d'),
            ]);

        $freshProduct = $this->product->fresh();
        expect($freshProduct->stock)->toBe(10.0);
        expect($freshProduct->stock_calculate)->toBe(10.0);

        // Product should appear in inStock scope
        $inStockProducts = Product::inStock()->where('type', 'product')->pluck('id');
        expect($inStockProducts)->toContain($this->product->id);
    });

    test('product stock stays correct after stock depletion and replenishment cycle', function () {
        $user = User::first();

        // Add initial stock
        $stock = Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 10,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);
        RecalculateEvent::dispatch(collect([$this->product]), []);
        expect($this->product->fresh()->stock)->toBe(10.0);

        // Deplete all stock (simulate selling)
        $stock->stock = 0;
        $stock->save();
        RecalculateEvent::dispatch(collect([$this->product]), []);

        // FIFO: depleted entries are filtered out — stock should be 0
        expect($this->product->fresh()->stock_calculate)->toBe(0);

        // Product should NOT appear in inStock scope
        $inStockProducts = Product::inStock()->where('type', 'product')->pluck('id');
        expect($inStockProducts)->not()->toContain($this->product->id);

        // Replenish stock via API
        $this->actingAs($user, 'sanctum')
            ->postJson("/api/master/product/{$this->product->id}/stock", [
                'stock' => 20,
                'initial_price' => 11000,
                'selling_price' => 21000,
                'date' => now()->format('Y-m-d'),
            ]);

        // Product should now have stock = 20 with correct prices
        $freshProduct = $this->product->fresh();
        expect($freshProduct->stock)->toBe(20.0);
        expect($freshProduct->initial_price)->toBe(11000.0);
        expect($freshProduct->selling_price)->toBe(21000.0);

        // Product should appear in inStock scope again
        $inStockProducts = Product::inStock()->where('type', 'product')->pluck('id');
        expect($inStockProducts)->toContain($this->product->id);
    });

    test('selling price does not become null after stock depletion (Bug #17)', function () {
        // Set up an initial stock entry and deplete it
        Stock::factory()->createQuietly([
            'product_id' => $this->product->id,
            'stock' => 0,
            'initial_price' => 8000,
            'selling_price' => 15000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        RecalculateEvent::dispatch(collect([$this->product]), []);

        $freshProduct = $this->product->fresh();
        // When all stock is depleted, prices should fall back to the product's existing prices
        // NOT become null (which was the Bug #17 behavior)
        expect($freshProduct->selling_price)->not->toBeNull();
        expect($freshProduct->initial_price)->not->toBeNull();
    });
});