<?php

use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

/**
 * A paid sale row inserted straight into the table: the factory cannot fill
 * `money_changes` and the other NOT NULL columns the model guards.
 */
function insertSelling(?int $userId): int
{
    return DB::table('sellings')->insertGetId([
        'date' => now()->toDateString(),
        'code' => 'SELL'.str_pad((string) (DB::table('sellings')->count() + 1), 4, '0', STR_PAD_LEFT),
        'payed_money' => 10000,
        'money_changes' => 0,
        'total_price' => 10000,
        'total_qty' => 1,
        'user_id' => $userId,
        'is_paid' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

test('the cashier filter lists the shop accounts without loading them whole', function () {
    User::factory()->count(3)->create();

    $options = User::query()
        ->get(['id', 'name', 'email'])
        ->mapWithKeys(fn (User $user) => [$user->id => $user->cashier_name]);

    expect($options)->toHaveCount(User::count());
});

test('backfilling a sale\'s missing values works a page at a time', function () {
    $product = Product::factory()->create(['initial_price' => 5000, 'selling_price' => 10000, 'stock' => 10]);
    $user = User::first();

    $ids = collect(range(1, 3))->map(function () use ($user, $product) {
        $id = insertSelling($user->id);
        SellingDetail::factory()->create([
            'selling_id' => $id,
            'product_id' => $product->id,
            'cost' => 0,
            'price' => 10000,
            'qty' => 1,
        ]);

        return $id;
    });

    DB::enableQueryLog();
    Artisan::call('app:fill-selling-missing-value');
    $log = DB::getQueryLog();
    DB::disableQueryLog();

    $wholeTable = array_filter(
        $log,
        fn (array $q) => trim($q['query']) === 'select * from `sellings` where `sellings`.`deleted_at` is null',
    );
    $pages = array_filter(
        $log,
        fn (array $q) => str_contains($q['query'], 'from `sellings`')
            && preg_match('/order by `id` asc limit \\d+/', $q['query']),
    );

    expect($wholeTable)->toBe([])
        ->and($pages)->not->toBe([]);

    foreach ($ids as $id) {
        expect((float) Selling::find($id)->total_cost)->toBeGreaterThan(0.0);
    }
});

test('the owner backfill command pages as well', function () {
    $ids = collect(range(1, 2))->map(fn () => insertSelling(null));

    DB::enableQueryLog();
    Artisan::call('app:fill-the-user-id-in-selling-with-owner-user-id');
    $log = DB::getQueryLog();
    DB::disableQueryLog();

    $wholeTable = array_filter(
        $log,
        fn (array $q) => trim($q['query']) === 'select * from `sellings` where `sellings`.`deleted_at` is null',
    );

    expect($wholeTable)->toBe([])
        ->and(Selling::whereIn('id', $ids)->whereNull('user_id')->count())->toBe(0);
});
