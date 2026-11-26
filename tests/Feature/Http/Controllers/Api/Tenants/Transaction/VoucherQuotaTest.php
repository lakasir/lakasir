<?php

use App\Models\Tenants\Product;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher;
use Illuminate\Testing\TestResponse;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    $this->product = Product::factory()->create(['selling_price' => 20000, 'initial_price' => 10000]);
});

function sellWith(string $code, array $extra = []): TestResponse
{
    return actingAs(User::first())->postJson('/api/transaction/selling', array_merge([
        'payed_money' => 20000,
        'friend_price' => false,
        'voucher' => $code,
        'products' => [
            ['product_id' => test()->product->id, 'qty' => 1],
        ],
    ], $extra));
}

test('a rejected sale does not use up the voucher', function () {
    $voucher = Voucher::factory()->create(['kuota' => 5]);

    // The total the app expected is not what the server computes: a 422
    // before any write.
    sellWith($voucher->code, ['expected_total' => 1])->assertUnprocessable();

    expect($voucher->fresh()->kuota)->toBe(5);
});

test('a successful sale uses up one voucher', function () {
    $voucher = Voucher::factory()->create(['kuota' => 5]);

    sellWith($voucher->code)->assertOk();

    expect($voucher->fresh()->kuota)->toBe(4);
});

test('the last voucher of the quota cannot be used twice', function () {
    $voucher = Voucher::factory()->create(['kuota' => 1]);

    sellWith($voucher->code)->assertOk();
    sellWith($voucher->code)->assertUnprocessable()->assertJsonValidationErrors('voucher');

    expect($voucher->fresh()->kuota)->toBe(0);
});
