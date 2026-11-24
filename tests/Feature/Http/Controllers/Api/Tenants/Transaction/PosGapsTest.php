<?php

use App\Constants\VoucherType;
use App\Http\Resources\ProductCollection;
use App\Models\Tenants\Category;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Stock;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    $this->category = Category::factory()->create();
    $this->product = Product::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Kopi',
        'initial_price' => 5000,
        'selling_price' => 10000,
        'stock' => 10,
        'is_non_stock' => false,
        'type' => 'product',
    ]);
    Stock::factory()->createQuietly([
        'product_id' => $this->product->id,
        'stock' => 10,
        'initial_price' => 5000,
        'selling_price' => 10000,
        'type' => 'in',
        'is_ready' => true,
        'date' => now()->format('Y-m-d'),
    ]);
});

/** A checkout body for $qty of the product. */
function saleOf(array $extra = []): array
{
    return array_merge([
        'payed_money' => 10000,
        'friend_price' => false,
        'expected_total' => 10000,
        'products' => [['product_id' => test()->product->id, 'qty' => 1]],
    ], $extra);
}

test('a service product sells even when its stock is zero', function () {
    $service = Product::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Jasa Bungkus',
        'initial_price' => 0,
        'selling_price' => 5000,
        'stock' => 0,
        'is_non_stock' => false,
        'type' => 'service',
    ]);

    actingAs(User::first())->postJson('/api/transaction/selling', [
        'payed_money' => 5000,
        'friend_price' => false,
        'expected_total' => 5000,
        'products' => [['product_id' => $service->id, 'qty' => 1]],
    ])->assertOk();

    expect(Selling::count())->toBe(1);
});

test('a flat voucher bigger than the cart is capped the same way by the check and the sale', function () {
    $voucher = Voucher::factory()->create([
        'code' => 'BESAR',
        'type' => VoucherType::flat,
        'nominal' => 50000,
        'minimal_buying' => 0,
    ]);

    $checked = actingAs(User::first())->postJson('/api/transaction/voucher/check', [
        'code' => 'BESAR',
        'total_price' => 10000,
    ])->assertOk();

    // The check caps the discount at the cart total.
    expect((float) $checked->json('data.discount'))->toEqual(10000.0)
        ->and((float) $checked->json('data.total_after_discount'))->toEqual(0.0);

    // ... and the sale does too, instead of rejecting "discount greater than subtotal".
    actingAs(User::first())->postJson('/api/transaction/selling', saleOf([
        'voucher' => 'BESAR',
        'payed_money' => 0,
        'expected_total' => 0,
    ]))->assertOk();

    $selling = Selling::first();
    expect((float) $selling->discount_price)->toEqual(10000.0)
        ->and((float) $selling->grand_total_price)->toEqual(0.0);
});

test('the voucher check reports its minimum purchase', function () {
    Voucher::factory()->create([
        'code' => 'MINIMAL',
        'nominal' => 2000,
        'minimal_buying' => 50000,
    ]);

    actingAs(User::first())->postJson('/api/transaction/voucher/check', [
        'code' => 'MINIMAL',
        'total_price' => 60000,
    ])->assertOk()
        ->assertJsonPath('data.minimal_buying', 50000);
});

test('an unknown member is a 422, not a 500', function () {
    actingAs(User::first())->postJson('/api/transaction/selling', saleOf([
        'member_id' => 999999,
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors('member_id');
});

test('the product list resource renders a product whose relation is absent', function () {
    $product = Product::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Tanpa Kategori',
    ]);

    // The schema's foreign key keeps products.category_id valid, so the
    // resource cannot crash on a dangling id in practice — but it must not
    // dereference a null relation if one ever appears. It is read with `?->`.
    $product->setRelation('category', null);

    $rendered = (new ProductCollection($product))->resolve();

    expect($rendered['category_id'])->toBeNull()
        ->and($rendered['category'])->toBeNull()
        ->and($rendered['name'])->toBe('Tanpa Kategori');
});

test('a wrong password for an unverified account says the password is wrong', function () {
    $user = User::factory()->create([
        'email' => 'belum@verifikasi.test',
        'password' => bcrypt('benar123'),
        'email_verified_at' => null,
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'belum@verifikasi.test',
        'password' => 'salah123',
    ])->assertUnprocessable();

    expect(json_encode($response->json('errors')))->not->toContain('verified');
});
