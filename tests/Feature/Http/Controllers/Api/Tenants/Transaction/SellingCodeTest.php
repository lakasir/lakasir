<?php

use App\Models\Company;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Models\Tenants\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    $product = Product::factory()->create([
        'category_id' => Category::factory()->create()->id,
        'selling_price' => 10000,
        'stock' => 100,
        'is_non_stock' => false,
        'type' => 'product',
    ]);

    $this->sell = fn () => actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 10000,
        'friend_price' => false,
        'expected_total' => 10000,
        'products' => [['product_id' => $product->id, 'qty' => 1]],
    ]);
});

test('sale codes come from the row id, one per sale', function () {
    ($this->sell)()->assertOk();
    ($this->sell)()->assertOk();
    ($this->sell)()->assertOk();

    // The code follows the row id, and ids are global now, so a shop's codes
    // are unique but do not restart at 1 on every install.
    $codes = Selling::orderBy('id')->pluck('code')->all();

    expect($codes)->toHaveCount(3)
        ->and(array_unique($codes))->toHaveCount(3)
        ->and($codes[0])->toMatch('/^SELL\\d+$/');
});

test('a deleted sale leaves no duplicate to reuse', function () {
    ($this->sell)()->assertOk();
    ($this->sell)()->assertOk();

    // A row count would reuse the code of the removed row.
    $selling = Selling::orderByDesc('id')->first();
    $removed = $selling->code;
    SellingDetail::where('selling_id', $selling->id)->delete();
    $selling->delete();
    ($this->sell)()->assertOk();

    expect(Selling::pluck('code'))->not->toContain($removed)
        ->and(Selling::count())->toBe(2);
});

test('the database refuses a duplicate code', function () {
    ($this->sell)()->assertOk();

    $this->expectException(QueryException::class);

    // `code` is guarded on the model, so go straight at the column.
    DB::table('sellings')->insert([
        'company_id' => Company::currentId(),
        'date' => now()->toDateString(),
        'code' => Selling::first()->code,
        'payed_money' => 10000,
        'money_changes' => 0,
        'total_price' => 10000,
        'total_qty' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});
