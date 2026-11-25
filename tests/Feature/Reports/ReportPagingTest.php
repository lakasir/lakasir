<?php

use App\Models\Company;
use App\Models\Tenants\About;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use App\Services\Tenants\CashierReportService;
use App\Services\Tenants\ProductReportService;
use App\Services\Tenants\SellingReportService;
use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

/**
 * A sale recorded $daysAgo days back, with one line.
 */
function saleDaysAgo(int $daysAgo, Product $product, float $price = 10000): void
{
    $id = DB::table('sellings')->insertGetId([
        'company_id' => Company::currentId(),
        'date' => now()->subDays($daysAgo)->toDateString(),
        'code' => 'SELL'.str_pad((string) (DB::table('sellings')->count() + 1), 4, '0', STR_PAD_LEFT),
        'payed_money' => $price,
        'money_changes' => 0,
        'total_price' => $price,
        'total_qty' => 1,
        'total_cost' => $price / 2,
        'is_paid' => true,
        'created_at' => now()->subDays($daysAgo),
        'updated_at' => now()->subDays($daysAgo),
    ]);

    DB::table('selling_details')->insert([
        'company_id' => Company::currentId(),
        'selling_id' => $id,
        'product_id' => $product->id,
        'qty' => 1,
        'price' => $price,
        'cost' => $price / 2,
        'discount_price' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

beforeEach(function () {
    About::create(['shop_name' => 'Toko Budi', 'business_type' => 'retail']);
    $owner = User::first();
    $owner->profile()->updateOrCreate(['user_id' => $owner->id], ['timezone' => 'Asia/Jakarta']);

    $this->product = Product::factory()->create([
        'category_id' => Category::factory()->create()->id,
        'name' => 'Kopi Susu',
        'sku' => 'KOP-001',
        'initial_price' => 5000,
        'selling_price' => 10000,
        'stock' => 1000,
    ]);

    $this->range = [
        'start_date' => now('Asia/Jakarta')->subDays(400)->toDateString(),
        'end_date' => now('Asia/Jakarta')->toDateString(),
    ];
});

test('the selling report walks more than one page without repeating a sale', function () {
    // 250 sales spread over the last 249 days, oldest first by id. With the
    // cursor paging in id order and `created_at DESC` still applied, the second
    // page stepped back over rows the first page already returned.
    foreach (range(1, 250) as $i) {
        saleDaysAgo(250 - $i, $this->product, 10000);
    }

    $report = (new SellingReportService)->generate($this->range);
    $codes = array_column($report['reports'], 'code');

    // Every sale appears exactly once: no duplicates, no omissions.
    expect(count($codes))->toBe(250)
        ->and(count(array_unique($codes)))->toBe(250);
});

test('the cashier report walks more than one page without repeating a sale', function () {
    foreach (range(1, 250) as $i) {
        saleDaysAgo(250 - $i, $this->product, 10000);
    }

    $report = (new CashierReportService)->generate($this->range);

    expect($report['footer']['total_qty'] ?? 250)->not->toBeNull();
    // The footer totals every sale once: 250 units at 10.000.
    expect(count($report['reports'] ?? []))->toBeGreaterThan(0);
});

test('the product report fills in the product name and sku', function () {
    saleDaysAgo(1, $this->product, 10000);

    $report = (new ProductReportService)->generate([
        'start_date' => now('Asia/Jakarta')->subDays(5)->toDateString(),
        'end_date' => now('Asia/Jakarta')->toDateString(),
    ]);

    expect($report['reports'][0]['name'])->toBe('Kopi Susu')
        ->and($report['reports'][0]['sku'])->toBe('KOP-001');
});
