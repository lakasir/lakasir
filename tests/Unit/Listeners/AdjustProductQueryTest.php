<?php

use App\Console\Commands\RecalculateAllProduct;
use App\Events\RecalculateEvent;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Setting::set('selling_method', 'fifo');
    Cache::clear();

    $category = Category::factory()->create();
    $this->products = collect(range(1, 5))->map(function (int $i) use ($category) {
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => "Produk $i",
            'initial_price' => 10000,
            'selling_price' => 20000,
            'stock' => 0,
            'is_non_stock' => false,
            'type' => 'product',
            'show' => true,
        ]);
        Stock::factory()->createQuietly([
            'product_id' => $product->id,
            'stock' => 5,
            'initial_price' => 10000,
            'selling_price' => 20000,
            'type' => 'in',
            'is_ready' => true,
            'date' => now()->format('Y-m-d'),
        ]);

        return $product;
    });
});

test('recalculating 5 products does not cost a query per product', function () {
    DB::enableQueryLog();
    RecalculateEvent::dispatch($this->products, []);
    $queries = count(DB::getQueryLog());
    DB::disableQueryLog();

    // Per product the listener used to ask 3 accessors, each running the stock
    // query twice plus reading the setting three times: 51 queries for 5
    // products before this change, 16 after.

    expect($queries)->toBeLessThan(25);

    foreach ($this->products as $product) {
        expect($product->fresh()->stock)->toBe(5.0);
    }
});

test('the recalculate command pages through the products instead of loading them all', function () {
    $command = new RecalculateAllProduct;

    DB::enableQueryLog();
    $command->handle();
    $log = DB::getQueryLog();
    DB::disableQueryLog();

    // chunkById reads a page ordered by id with a `where id > ?` cursor.
    // chunkById pages with `order by id asc limit <chunk>`, never `select *`
    // of the whole table.
    $pages = array_filter(
        $log,
        fn (array $q) => str_contains($q['query'], 'from `products`')
            && preg_match('/order by `id` asc limit \d+/', $q['query']),
    );
    $wholeTable = array_filter(
        $log,
        fn (array $q) => trim($q['query']) === 'select * from `products` where `products`.`deleted_at` is null',
    );

    expect($pages)->not->toBe([])
        ->and($wholeTable)->toBe([]);

    foreach ($this->products as $product) {
        expect($product->fresh()->stock)->toBe(5.0);
    }
});
