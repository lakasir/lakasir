<?php

use App\Models\Tenants\About;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Models\Tenants\User;
use App\Services\Tenants\ProductReportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    About::create(['shop_name' => 'Toko Budi', 'business_type' => 'retail']);
    $owner = User::first();
    $owner->profile()->updateOrCreate(['user_id' => $owner->id], ['timezone' => 'Asia/Jakarta']);

    $category = Category::factory()->create();
    $products = collect(range(1, 6))->map(fn (int $i) => Product::factory()->create([
        'category_id' => $category->id,
        'name' => "Produk $i",
        'initial_price' => 5000,
        'selling_price' => 10000,
        'stock' => 100,
    ]));

    // Three sales of three lines each: 10.000 price, 5.000 cost.
    foreach ([true, true, false] as $paid) {
        $selling = Selling::create([
            // The report reads its window in the shop's timezone, so the row
            // has to be dated there too: between 00:00 and 07:00 Jakarta the
            // UTC date is still yesterday and the sale falls outside the window.
            'date' => now('Asia/Jakarta')->toDateString(),
            'payed_money' => 30000,
            'money_changes' => 0,
            'total_price' => 30000,
            'total_qty' => 3,
            'is_paid' => $paid,
        ]);
        foreach ($products->take(3) as $product) {
            SellingDetail::create([
                'selling_id' => $selling->id,
                'product_id' => $product->id,
                'qty' => 1,
                'price' => 10000,
                'cost' => 5000,
                'discount_price' => 0,
            ]);
        }
    }

    $this->range = [
        'start_date' => now('Asia/Jakarta')->toDateString(),
        'end_date' => now('Asia/Jakarta')->toDateString(),
    ];
});

test('the product report does not query each product\'s sales', function () {
    DB::enableQueryLog();
    $report = (new ProductReportService)->generate($this->range);
    $log = DB::getQueryLog();
    DB::disableQueryLog();

    $perProduct = array_filter(
        $log,
        fn (array $q) => str_contains($q['query'], 'from `selling_details`')
            && str_contains($q['query'], '`selling_details`.`product_id` = ?'),
    );

    // One row per product sold in the window, built from grouped rows.
    expect(count($report['reports']))->toBeGreaterThan(0)
        ->and($perProduct)->toBe([]);
});

test('the product report totals what the sale lines add up to', function () {
    $report = (new ProductReportService)->generate($this->range);

    // Three sales of three lines each: 9 units, 9 x 10.000 sold, 9 x 5.000 cost.
    // Number::format uses the app locale's separators, so compare through it.
    expect($report['footer']['total_gross'])->toBe(Number::format(90000))
        ->and($report['footer']['total_cost'])->toBe(Number::format(45000))
        ->and($report['footer']['total_qty'])->toEqual(9)
        ->and($report['footer']['total_gross_profit'])->toBe(Number::format(45000));
});
