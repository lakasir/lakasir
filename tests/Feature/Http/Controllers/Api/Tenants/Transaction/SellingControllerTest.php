<?php

use App\Constants\VoucherType;
use App\Events\RecalculateEvent;
use App\Features\Discount;
use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;
use Illuminate\Support\Facades\Cache;
use Laravel\Pennant\Feature;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('cashier can create the selling transaction', function () {
    $user = User::first();
    $member = Member::factory()->create();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'member_id' => $member->getKey(),
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'total_price' => 20000,
        'total_cost' => 10000,
        'total_qty' => 1,
    ]);
});

test('cashier can create the selling transaction with member_id null', function () {
    $user = User::first();
    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'total_price' => 20000,
        'total_cost' => 10000,
        'total_qty' => 1,
    ]);
});

test('cashier can create the selling transaction with customer number', function () {
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'customer_number' => 123,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'total_price' => 20000,
        'total_cost' => 10000,
        'total_qty' => 1,
        'customer_number' => 123,
    ]);
});

test('cashier can create the selling with tax price', function () {
    Setting::set('default_tax', 10);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 22000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'total_price' => 22000,
        'tax_price' => 2000,
        'tax' => 10,
    ]);
});

test('cashier cannnot create the selling transaction if the cash drawer enabled but not opened', function () {
    Setting::set('cash_drawer_enabled', true);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('errors.cash_drawer.0', 'Cash drawer is not opened');
});

test('cashier can create the selling transaction if the cash drawer enabled and opened', function () {
    Setting::set('cash_drawer_enabled', true);
    $user = User::first();
    CashDrawer::create([
        'opened_by' => $user->id,
        'cash' => 0,
    ]);

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'cash_drawer_id' => CashDrawer::first()->id,
    ]);
});

test('cashier can create the sellings transaction with normal selling method with no added stock', function () {
    Setting::set('selling_method', 'normal');
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'payment_method_id' => PaymentMethod::first()->id,
    ]);
});

test('cashier can create the sellings transaction with normal selling method with added stock', function () {
    Setting::set('selling_method', 'normal');
    Stock::factory()
        ->createQuietly([
            'product_id' => $this->product->id,
            'date' => now()->subDay(),
            'stock' => 10,
        ]);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'payment_method_id' => PaymentMethod::first()->id,
    ]);
});

test('cashier can create the sellings transaction with normal selling method with added 0 stock', function () {
    Setting::set('selling_method', 'normal');
    Stock::factory()
        ->createQuietly([
            'product_id' => $this->product->id,
            'date' => now()->subDay(),
            'stock' => 10,
        ]);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'payment_method_id' => PaymentMethod::first()->id,
    ]);
});

test('cashier cannot create the sellings transaction with normal selling method with updated selling price', function () {
    Setting::set('selling_method', 'normal');
    /** @var Product $product */
    $product = $this->product->replicate();
    $product->save();
    Stock::factory()
        ->createQuietly([
            'product_id' => $product->id,
            'date' => now()->subDay(),
            'stock' => 0,
            'initial_price' => 20000,
            'selling_price' => 30000,
            'is_ready' => true,
        ]);
    Stock::factory()
        ->createQuietly([
            'product_id' => $product->id,
            'date' => now()->subDay(),
            'stock' => 20,
            'initial_price' => 20000,
            'selling_price' => 30000,
            'is_ready' => true,
        ]);
    RecalculateEvent::dispatch(collect([$product]), []);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('payed_money');
});

test('cashier can create the seling with manual discount', function () {
    Feature::activate(Discount::class);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
        'discount_price' => 1000,
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'discount_price' => 1000,
        'total_price' => 20000,
        'tax_price' => 0.00,
        'tax' => 0,
        'money_changes' => 1000,
    ]);
});

test('cashier can create the seling with manual discount using payed money less than total price', function () {
    Feature::activate(Discount::class);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 19000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
        'discount_price' => 1000,
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'discount_price' => 1000,
        'total_price' => 20000,
        'payed_money' => 19000,
        'tax_price' => 0.00,
        'tax' => 0,
        'money_changes' => 0,
    ]);
});

test('cashier can create the selling with discount per item', function () {
    Feature::activate(Discount::class);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id, // price 20000
                'qty' => 1,
                'discount_price' => 1000,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'payed_money' => 20000,
        'discount_price' => 0,
        'total_price' => 20000,
        'tax_price' => 0.00,
        'tax' => 0,
        'money_changes' => 1000,
        'total_discount_per_item' => 1000,
    ]);

    $this->assertDatabaseHas('selling_details', [
        'product_id' => $this->product->id,
        'discount_price' => 1000,
        'price' => 20000,
        'cost' => 10000,
    ]);
});

test('cashier can create the selling with discount per item using payed money less than total price', function () {
    Feature::activate(Discount::class);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 15000,
        'friend_price' => false,
        'products' => [
            [
                'product_id' => $this->product->id, // price 20000
                'qty' => 1,
                'discount_price' => 5000,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'payed_money' => 15000,
        'discount_price' => 0,
        'total_price' => 20000,
        'tax_price' => 0.00,
        'tax' => 0,
        'money_changes' => 0,
        'total_discount_per_item' => 5000,
    ]);

    $this->assertDatabaseHas('selling_details', [
        'product_id' => $this->product->id,
        'discount_price' => 5000,
        'price' => 20000,
        'cost' => 10000,
    ]);
});

test('cashier can create the selling with voucher flat type', function () {
    $voucher = Voucher::factory()->create();
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'voucher' => $voucher->code,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'discount_price' => 2000,
        'total_price' => 20000,
        'tax_price' => 0.00,
        'tax' => 0,
        'money_changes' => 2000,
    ]);
});

test('cashier can create the selling with voucher percentage type', function () {
    $voucher = Voucher::factory()->create([
        'type' => VoucherType::percentage,
        'nominal' => 20,
    ]);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'voucher' => $voucher->code,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk()
        ->assertJsonPath('message', 'success create selling');

    $this->assertDatabaseHas('sellings', [
        'discount_price' => 4000,
        'total_price' => 20000,
        'tax_price' => 0.00,
        'tax' => 0,
        'money_changes' => 4000,
    ]);
});

test('cashier cannot create the sellig with expired voucher', function () {
    $voucher = Voucher::factory()->create([
        'start_date' => now()->subDay(10),
        'expired' => now()->subDay(1),
    ]);
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'voucher' => $voucher->code,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('voucher');
});

test('cashier cannot mass assign server computed selling attributes', function () {
    $user = User::first();
    $other = User::factory()->create();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'is_paid' => false,
        'user_id' => $other->getKey(),
        'total_cost' => 0,
        'code' => 'X',
        'cash_drawer_id' => 999,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
            ],
        ],
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('sellings', [
        'user_id' => $user->getKey(),
        'is_paid' => true,
        'total_cost' => 10000,
        'cash_drawer_id' => null,
    ]);
});

test('friend price selling computes total cost on the server', function () {
    $user = User::first();

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 15000,
        'friend_price' => true,
        'note' => 'regular customer',
        'total_price' => 15000,
        'total_qty' => 1,
        'total_cost' => 0,
        'products' => [
            [
                'product_id' => $this->product->id,
                'qty' => 1,
                'price' => 15000,
                'discount_price' => 0,
            ],
        ],
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('sellings', [
        'friend_price' => true,
        'total_price' => 20000,
        'discount_price' => 5000,
        'money_changes' => 0,
        'total_cost' => 10000,
    ]);
});

beforeEach(function () {
    Setting::set('selling_method', 'fifo');
    Cache::clear();
    $product = Product::factory()
        ->create([
            'name' => 'test',
            'initial_price' => 10000,
            'selling_price' => 20000,
            'stock' => 10,
        ]);

    $this->product = $product;
});

test('cashier can add a note to each selling line', function () {
    $user = User::first();
    $other = Product::factory()->create([
        'initial_price' => 5000,
        'selling_price' => 8000,
        'stock' => 10,
    ]);

    $response = actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 50000,
        'friend_price' => false,
        'products' => [
            ['product_id' => $this->product->id, 'qty' => 1, 'note' => 'no sugar, extra ice'],
            ['product_id' => $other->id, 'qty' => 1],
        ],
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('selling_details', [
        'product_id' => $this->product->id,
        'note' => 'no sugar, extra ice',
    ]);
    $this->assertDatabaseHas('selling_details', [
        'product_id' => $other->id,
        'note' => null,
    ]);

    $sellingId = $response->json('data.id');

    actingAs($user)->getJson("/api/transaction/selling/{$sellingId}")
        ->assertOk()
        ->assertJsonPath('data.selling_details.0.note', 'no sugar, extra ice')
        ->assertJsonPath('data.selling_details.1.note', null);
});

test('selling line note must not exceed 255 characters', function () {
    $user = User::first();

    actingAs($user)->postJson('/api/transaction/selling', [
        'payed_money' => 20000,
        'friend_price' => false,
        'products' => [
            ['product_id' => $this->product->id, 'qty' => 1, 'note' => str_repeat('a', 256)],
        ],
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('products.0.note');
});
