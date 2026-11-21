<?php

use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Receivable;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Cache;
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
    $this->credit = PaymentMethod::create([
        'name' => 'Credit',
        'is_cash' => false,
        'is_debit' => false,
        'is_credit' => true,
        'is_wallet' => false,
    ]);
});

function apiCheckout(array $payload)
{
    return actingAs(test()->user)->postJson('/api/transaction/selling', array_merge([
        'friend_price' => false,
        'products' => [['product_id' => test()->product->id, 'qty' => 1]],
    ], $payload));
}

test('a credit sale needs a member and a due date', function () {
    apiCheckout(['payment_method_id' => $this->credit->id, 'payed_money' => 0])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['member_id', 'due_date']);

    expect(Selling::count())->toBe(0);
});

test('a credit sale with a member and a due date creates a receivable', function () {
    $member = Member::factory()->create();

    apiCheckout([
        'payment_method_id' => $this->credit->id,
        'payed_money' => 0,
        'member_id' => $member->id,
        'due_date' => now()->addMonth()->format('Y-m-d'),
    ])->assertOk();

    $receivable = Receivable::first();
    expect($receivable)->not->toBeNull()
        ->and($receivable->member_id)->toBe($member->id)
        ->and($receivable->due_date)->toStartWith(now()->addMonth()->format('Y-m-d'));
});

test('a member that does not exist is rejected', function () {
    apiCheckout(['payed_money' => 20000, 'member_id' => 999999])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['member_id']);
});

test('a payment method that does not exist is rejected', function () {
    apiCheckout(['payed_money' => 20000, 'payment_method_id' => 999999])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['payment_method_id']);
});
