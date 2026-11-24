<?php

use App\Models\Tenants\Selling;
use App\Models\Tenants\User;
use App\Services\Tenants\DashboardService;
use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

/**
 * A paid sale recorded today with the given numbers.
 */
function paidSale(array $numbers): Selling
{
    return Selling::create(array_merge([
        'date' => now()->toDateString(),
        'payed_money' => 100000,
        'money_changes' => 0,
        'total_qty' => 1,
        'is_paid' => true,
    ], $numbers));
}

test('revenue is the price after discounts and tax, gross profit subtracts the cost', function () {
    // 100.000 price, 10.000 tax, 5.000 order discount, 2.000 line discount,
    // 60.000 cost.
    paidSale([
        'total_price' => 110000,
        'tax_price' => 10000,
        'discount_price' => 5000,
        'total_discount_per_item' => 2000,
        'total_cost' => 60000,
    ]);

    $start = now()->startOfDay();
    $end = now()->endOfDay();
    $service = new DashboardService;

    expect($service->revenue($start, $end))->toEqual(93000.0)
        ->and($service->grossProfit($start, $end))->toEqual(33000.0);
});

test('unpaid sales are left out of every metric', function () {
    paidSale(['total_price' => 20000, 'tax_price' => 0, 'discount_price' => 0, 'total_discount_per_item' => 0, 'total_cost' => 10000]);
    // `is_paid` is guarded on the model, so mark the row unpaid in the column.
    DB::table('sellings')->insert([
        'date' => now()->toDateString(),
        'code' => 'SELL9999',
        'payed_money' => 0,
        'money_changes' => 0,
        'total_price' => 500000,
        'total_qty' => 1,
        'is_paid' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $start = now()->startOfDay();
    $end = now()->endOfDay();
    $service = new DashboardService;

    expect($service->revenue($start, $end))->toEqual(20000.0)
        ->and($service->sales($start, $end))->toBe(1);
});

test('the API answers the documented keys, with the old misspelling kept', function () {
    paidSale(['total_price' => 110000, 'tax_price' => 10000, 'discount_price' => 5000, 'total_discount_per_item' => 2000, 'total_cost' => 60000]);

    $owner = User::first();
    $owner->profile()->updateOrCreate(['user_id' => $owner->id], ['timezone' => 'Asia/Jakarta']);

    $today = now('Asia/Jakarta')->toDateString();

    $revenue = actingAs($owner)
        ->getJson('/api/transaction/dashboard/total-revenue?filter_type=yesterday&timezone=Asia/Jakarta')
        ->assertOk();

    // Nothing sold yesterday, so the comparison is zero and the window is
    // not today's: the sale must not leak in.
    expect($revenue->json('data.total_revenue'))->toEqual(0);

    $todayRevenue = actingAs($owner)
        ->getJson('/api/transaction/dashboard/total-revenue?timezone=Asia/Jakarta')
        ->assertOk()
        ->assertJsonStructure(['data' => ['total_revenue', 'total_prevous_revenue', 'total_previous_revenue', 'percentage_change']]);

    expect((float) $todayRevenue->json('data.total_revenue'))->toEqual(93000.0);

    actingAs($owner)
        ->getJson('/api/transaction/dashboard/total-gross-profit?timezone=Asia/Jakarta')
        ->assertOk()
        ->assertJsonPath('data.total_gross_profit', fn ($value) => (float) $value === 33000.0);

    actingAs($owner)
        ->getJson('/api/transaction/dashboard/total-sales?timezone=Asia/Jakarta')
        ->assertOk()
        ->assertJsonPath('data.total_sales', 1);
});

test('a percentage change is not divided by zero', function () {
    $service = new DashboardService;

    expect($service->percentageChange(500, 0))->toBe(0)
        ->and($service->percentageChange(150, 100))->toBe(50);
});
