<?php

use App\Features\Discount;
use App\Features\SellingTax;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;
use Illuminate\Support\Facades\Cache;
use Laravel\Pennant\Feature;
use Spatie\Permission\Models\Permission;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Setting::set('selling_method', 'fifo');
    Cache::clear();
    $this->user = User::first();
    $this->product = Product::factory()->create([
        'initial_price' => 10000,
        'selling_price' => 20000,
        'stock' => 20,
    ]);
});

function checkout(array $payload)
{
    return actingAs(test()->user)->postJson('/api/transaction/selling', array_merge([
        'friend_price' => false,
        'payed_money' => 100000,
    ], $payload));
}

test('a price sent by the client is ignored when it is not a friend price sale', function () {
    checkout([
        'products' => [['product_id' => $this->product->id, 'qty' => 2, 'price' => 1]],
    ])->assertOk();

    $this->assertDatabaseHas('sellings', ['total_price' => 40000, 'money_changes' => 60000]);
    $this->assertDatabaseHas('selling_details', ['product_id' => $this->product->id, 'price' => 40000]);
});

test('a tax sent by the client is ignored and the store tax applies', function () {
    Setting::set('default_tax', 10);

    checkout([
        'tax' => 0,
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertOk();

    $this->assertDatabaseHas('sellings', ['tax' => 10, 'tax_price' => 2000, 'total_price' => 22000]);
});

test('no tax is charged when the selling tax feature is off', function () {
    Setting::set('default_tax', 10);
    Feature::deactivate(SellingTax::class);

    checkout([
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertOk();

    $this->assertDatabaseHas('sellings', ['tax' => 0, 'tax_price' => 0, 'total_price' => 20000]);
});

test('tax is charged on the price after discounts', function () {
    Setting::set('default_tax', 10);
    Feature::activate(Discount::class);

    // subtotal 40.000, line discount 2.000, order discount 8.000 → taxable 30.000 → tax 3.000
    checkout([
        'payed_money' => 33000,
        'discount_price' => 8000,
        'products' => [['product_id' => $this->product->id, 'qty' => 2, 'discount_price' => 2000]],
    ])->assertOk();

    $this->assertDatabaseHas('sellings', [
        'tax_price' => 3000,
        'total_price' => 43000,
        'total_discount_per_item' => 2000,
        'discount_price' => 8000,
        'money_changes' => 0,
    ]);

    expect(Selling::latest('id')->first()->grand_total_price)->toEqual(33000);
});

test('discounts are rejected while the discount feature is off', function () {
    checkout([
        'discount_price' => 1000,
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertUnprocessable()->assertJsonValidationErrors('discount_price');

    checkout([
        'products' => [['product_id' => $this->product->id, 'qty' => 1, 'discount_price' => 1000]],
    ])->assertUnprocessable()->assertJsonValidationErrors('products.0.discount_price');
});

test('a line discount cannot exceed the line price', function () {
    Feature::activate(Discount::class);

    checkout([
        'products' => [['product_id' => $this->product->id, 'qty' => 1, 'discount_price' => 20001]],
    ])->assertUnprocessable()->assertJsonValidationErrors('products.0.discount_price');
});

test('a price unit of another product is rejected', function () {
    $other = Product::factory()->create(['selling_price' => 5000, 'initial_price' => 1000, 'stock' => 10]);
    $unit = PriceUnit::factory()->create(['product_id' => $other->id, 'selling_price' => 40000]);

    checkout([
        'products' => [['product_id' => $this->product->id, 'qty' => 1, 'price_unit_id' => $unit->id]],
    ])->assertUnprocessable()->assertJsonValidationErrors('products.0.price_unit_id');
});

test('a total the client expects that differs from the server is rejected with the server breakdown', function () {
    Setting::set('default_tax', 10);

    checkout([
        'expected_total' => 20000,
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('expected_total')
        ->assertJsonPath('totals.subtotal', 20000)
        ->assertJsonPath('totals.tax_price', 2000)
        ->assertJsonPath('totals.amount_due', 22000);

    expect(Selling::count())->toBe(0);

    checkout([
        'expected_total' => 22000,
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertOk();
});

test('a failed checkout does not use up the voucher', function () {
    $voucher = Voucher::factory()->create(['kuota' => 5]);

    checkout([
        'payed_money' => 100,
        'voucher' => $voucher->code,
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertUnprocessable();

    expect($voucher->fresh()->kuota)->toBe(5);

    checkout([
        'voucher' => $voucher->code,
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertOk();

    expect($voucher->fresh()->kuota)->toBe(4);
});

test('friend price needs the use friend price permission', function () {
    $cashier = User::factory()->create();
    $cashier->givePermissionTo(Permission::where('name', 'create selling')->where('guard_name', 'web')->first());
    $this->user = $cashier;

    checkout([
        'friend_price' => true,
        'total_price' => 15000,
        'note' => 'regular',
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertForbidden();
});

test('friend price needs a reason', function () {
    checkout([
        'friend_price' => true,
        'total_price' => 15000,
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertUnprocessable()->assertJsonValidationErrors('note');
});

test('friend price cannot go below cost or above the normal total', function (float $total) {
    checkout([
        'friend_price' => true,
        'total_price' => $total,
        'note' => 'regular',
        'products' => [['product_id' => $this->product->id, 'qty' => 1]],
    ])->assertUnprocessable()->assertJsonValidationErrors('total_price');
})->with([
    'below cost' => 9999,
    'above normal total' => 20001,
]);

test('friend price keeps the normal total and records what was given away', function () {
    checkout([
        'friend_price' => true,
        'total_price' => 15000,
        'payed_money' => 20000,
        'note' => 'regular',
        'products' => [['product_id' => $this->product->id, 'qty' => 1, 'price' => 1]],
    ])->assertOk();

    $selling = Selling::latest('id')->first();
    expect((bool) $selling->friend_price)->toBeTrue()
        ->and($selling->total_price)->toEqual(20000)
        ->and($selling->discount_price)->toEqual(5000)
        ->and($selling->grand_total_price)->toEqual(15000)
        ->and($selling->money_changes)->toEqual(5000)
        ->and($selling->note)->toBe('regular');

    $this->assertDatabaseHas('selling_details', ['product_id' => $this->product->id, 'price' => 20000]);
});
