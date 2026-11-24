<?php

use App\Constants\VoucherType;
use App\Features\Discount;
use App\Models\Tenants\Category;
use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\PriceUnit;
use App\Models\Tenants\Product;
use App\Models\Tenants\Receivable;
use App\Models\Tenants\ReceivableItem;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;
use Illuminate\Support\Facades\Cache;
use Laravel\Pennant\Feature;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Setting::set('selling_method', 'fifo');
    Cache::clear();

    $this->product = Product::factory()->create([
        'category_id' => Category::factory()->create()->id,
        'name' => 'Kopi',
        'initial_price' => 5000,
        'selling_price' => 10000,
        'stock' => 20,
        'is_non_stock' => false,
        'type' => 'product',
    ]);
    Stock::factory()->createQuietly([
        'product_id' => $this->product->id,
        'stock' => 20,
        'initial_price' => 5000,
        'selling_price' => 10000,
        'type' => 'in',
        'is_ready' => true,
        'date' => now()->format('Y-m-d'),
    ]);
});

function creditMethod(): PaymentMethod
{
    return PaymentMethod::firstOrCreate(
        ['name' => 'Kasbon'],
        ['is_credit' => true],
    );
}

test('a credit sale becomes a receivable for its member', function () {
    $member = Member::factory()->create();
    $method = creditMethod();

    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 0,
        'friend_price' => false,
        'payment_method_id' => $method->id,
        'member_id' => $member->id,
        'due_date' => now()->addWeek()->toDateString(),
        'expected_total' => 20000,
        'products' => [['product_id' => $this->product->id, 'qty' => 2]],
    ])->assertOk();

    $receivable = Receivable::first();

    expect($receivable)->not->toBeNull()
        ->and($receivable->member_id)->toBe($member->id)
        ->and(ReceivableItem::where('receivable_id', $receivable->id)->count())->toBe(1);

    // A credit sale is not paid at the till: nothing was handed over.
    $selling = Selling::first();
    expect((float) $selling->money_changes)->toEqual(0.0)
        ->and((bool) $selling->is_paid)->toBeTrue();
});

test('a cash sale creates no receivable', function () {
    $cash = PaymentMethod::where('is_cash', true)->firstOrFail();

    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'payment_method_id' => $cash->id,
        'expected_total' => 20000,
        'products' => [['product_id' => $this->product->id, 'qty' => 2]],
    ])->assertOk();

    expect(Receivable::count())->toBe(0);
});

test('a price unit is sold at its own price and its own stock multiple', function () {
    $priceUnit = PriceUnit::create([
        'product_id' => $this->product->id,
        'unit' => 'BOX',
        'selling_price' => 90000,
        'stock' => 10,
    ]);

    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 90000,
        'friend_price' => false,
        'expected_total' => 90000,
        'products' => [[
            'product_id' => $this->product->id,
            'price_unit_id' => $priceUnit->id,
            'qty' => 1,
        ]],
    ])->assertOk();

    // One BOX = ten PCS: the line is priced at the box price and the stock
    // leaves in base units.
    $line = Selling::first()->sellingDetails()->first();
    expect((float) $line->price)->toEqual(90000.0)
        ->and((float) $line->qty)->toEqual(10.0)
        ->and((float) $line->cost)->toEqual(50000.0);
});

test('a price unit that belongs to another product is refused', function () {
    $other = Product::factory()->create(['category_id' => $this->product->category_id]);
    $priceUnit = PriceUnit::create([
        'product_id' => $other->id,
        'unit' => 'BOX',
        'selling_price' => 90000,
        'stock' => 10,
    ]);

    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 90000,
        'friend_price' => false,
        'expected_total' => 90000,
        'products' => [[
            'product_id' => $this->product->id,
            'price_unit_id' => $priceUnit->id,
            'qty' => 1,
        ]],
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('products.0.price_unit_id');
});

test('a voucher replaces the order discount instead of stacking on it', function () {
    Feature::activate(Discount::class);

    $voucher = Voucher::factory()->create([
        'code' => 'HEMAT',
        'type' => VoucherType::flat,
        'nominal' => 2000,
        'minimal_buying' => 0,
    ]);

    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 18000,
        'friend_price' => false,
        'voucher' => $voucher->code,
        'discount_price' => 3000,
        'expected_total' => 18000,
        'products' => [['product_id' => $this->product->id, 'qty' => 2]],
    ])->assertOk();

    // The voucher replaces the manual discount rather than stacking on it.
    $selling = Selling::first();
    expect((float) $selling->discount_price)->toEqual(2000.0);
});
