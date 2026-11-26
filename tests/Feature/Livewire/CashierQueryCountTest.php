<?php

use App\Features\ProductStock;
use App\Filament\Tenant\Pages\Cashier;
use App\Models\Tenants\CartItem;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Pennant\Feature;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

/**
 * The queries one cashier-grid render runs, over $products products with a
 * cart line on each. Measured on a single render: Livewire accumulates the
 * components it mounts within a test, so a second render in the same test
 * reports both.
 *
 * @return array<int, array{query: string}>
 */
function cashierRenderLog(int $products): array
{
    Setting::set('selling_method', 'fifo');
    Setting::set('currency', 'IDR');
    Cache::clear();

    // ProductObserver creates the opening stock row from the product's own
    // stock, but marks products non-stock when the feature is off.
    Feature::activate(ProductStock::class);

    $user = User::first();
    for ($i = 0; $i < $products; $i++) {
        $product = Product::factory()->create([
            'name' => "Produk $i",
            'stock' => 10,
            'selling_price' => 10000,
            'initial_price' => 5000,
            'is_non_stock' => false,
            'type' => 'product',
            'show' => true,
        ]);
        CartItem::factory()->createQuietly([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'qty' => 1,
            'price' => 10000,
        ]);
    }

    actingAs($user);
    Filament::setCurrentPanel(Filament::getPanel('tenant'));

    DB::enableQueryLog();
    Livewire::test(Cashier::class);
    $log = DB::getQueryLog();
    DB::disableQueryLog();

    return $log;
}

/**
 * How many queries in the log match $pattern, ignoring the ones whose statement
 * already names the product (the stock aggregate is a sub-select of it).
 *
 * @param  array<int, array{query: string}>  $log
 */
function countQueries(array $log, string $pattern): int
{
    return count(array_filter(
        $log,
        fn (array $q) => preg_match($pattern, $q['query']) && ! str_contains($q['query'], 'products'),
    ));
}

test('a grid of 8 products reads each relation once, not once per card', function () {
    $log = cashierRenderLog(8);

    // One query for the page's stock (inside the product select), one for the
    // cart lines, one for their price units — whatever the number of cards.
    expect(countQueries($log, '/^select .* from `stocks`/'))->toBe(0)
        ->and(countQueries($log, '/^select .* from `cart_items` where `user_id` = \? and `cart_items`\.`product_id` in/'))->toBe(1)
        ->and(countQueries($log, '/^select .* from `price_units` where `price_units`\.`product_id` in/'))->toBe(1)
        // CartItem::with('product') re-reads the products the page already has.
        ->and(countQueries($log, '/^select .* from `products` where `products`\.`id` in/'))->toBeLessThanOrEqual(2);
});

test('a card reads the stock from the aggregate, not one query per product', function () {
    cashierRenderLog(3);

    actingAs(User::first());
    $product = Product::withAvailableStock()->first();

    expect((float) $product->stock_calculate)->toEqual((float) $product->available_stock)
        ->and((float) $product->available_stock)->toEqual(10.0);
});

test('a cart line is read from the eager-loaded relation', function () {
    cashierRenderLog(2);

    actingAs(User::first());
    $product = Product::with(['cartItems'])->whereHas('cartItems')->first();

    DB::flushQueryLog();
    DB::enableQueryLog();
    $line = $product->cartItemForCashier();
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    expect($line)->not->toBeNull()
        ->and($queries)->toBe([]);

    // Without the eager load it falls back to a query, as before.
    DB::enableQueryLog();
    Product::find($product->id)->cartItemForCashier();
    expect(DB::getQueryLog())->toHaveCount(2);
    DB::disableQueryLog();
});
