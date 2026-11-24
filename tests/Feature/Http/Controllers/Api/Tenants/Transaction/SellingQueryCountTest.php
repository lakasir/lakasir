<?php

use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('a 10-line sale reads each product once, not once per stage', function () {
    Setting::set('selling_method', 'fifo');
    Cache::clear();

    $category = Category::factory()->create();
    $products = collect(range(1, 10))->map(fn (int $i) => Product::factory()->create([
        'category_id' => $category->id,
        'name' => "Produk $i",
        'initial_price' => 5000,
        'selling_price' => 10000,
        'stock' => 20,
        'is_non_stock' => false,
        'type' => 'product',
    ]));

    DB::enableQueryLog();
    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 100000,
        'friend_price' => false,
        'expected_total' => 100000,
        'products' => $products->map(fn (Product $p) => ['product_id' => $p->id, 'qty' => 1])->all(),
    ])->assertOk();
    $log = DB::getQueryLog();
    DB::disableQueryLog();

    // Before the batching the stock rule, the total calculation and the detail
    // writer each fetched the product: 40 lookups for 10 lines, plus 30 for the
    // batches. Now the cart's products arrive in one query and the per-line
    // work is bounded: the `exists` rule (one count per line) and the locked
    // batch read the stock service has to make.
    $productLookups = array_filter(
        $log,
        fn (array $q) => str_contains($q['query'], 'from `products` where `products`.`id` = ?'),
    );
    $stockLookups = array_filter(
        $log,
        fn (array $q) => str_contains($q['query'], 'from `stocks`') && str_contains($q['query'], 'product_id` = ?'),
    );

    expect(Selling::count())->toBe(1)
        ->and(count($log))->toBeLessThan(140)
        // At most one product read per line, not four.
        ->and(count($productLookups))->toBeLessThanOrEqual(10)
        // The locked batch read and its recalculation, per line.
        ->and(count($stockLookups))->toBeLessThanOrEqual(20);
});
