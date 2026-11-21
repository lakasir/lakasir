<?php

use App\Features\Discount;
use App\Filament\Tenant\Pages\Cashier;
use App\Models\Tenants\CartItem;
use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Illuminate\Support\Facades\Cache;
use Filament\Facades\Filament;
use Laravel\Pennant\Feature;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('the web cashier checks out with server totals and tax after discount', function () {
    Setting::set('selling_method', 'fifo');
    Setting::set('default_tax', 10);
    Feature::activate(Discount::class);
    Cache::clear();
    $user = User::first();
    $product = Product::factory()->create(['initial_price' => 10000, 'selling_price' => 20000, 'stock' => 10]);
    CartItem::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'qty' => 1,
        'price' => 20000,
        'discount_price' => 2000,
    ]);

    actingAs($user);
    Filament::setCurrentPanel(Filament::getPanel('tenant'));

    Livewire::test(Cashier::class)
        ->assertSet('total_price', 19800.0)
        ->set('cartDetail.payed_money', 19800)
        ->set('cartDetail.payment_method_id', 1)
        ->set('cartDetail.discount_price', '0')
        ->call('proceedThePayment')
        ->assertHasNoErrors();

    expect(Selling::count())->toBe(1);
    $this->assertDatabaseHas('sellings', [
        'total_price' => 21800,
        'tax_price' => 1800,
        'total_discount_per_item' => 2000,
        'money_changes' => 0,
    ]);
});

function cashierWithCart(array $productAttributes = []): array
{
    Setting::set('selling_method', 'fifo');
    Cache::clear();
    $user = User::first();
    $product = Product::factory()->create(array_merge([
        'initial_price' => 10000,
        'selling_price' => 20000,
        'stock' => 10,
    ], $productAttributes));
    CartItem::factory()->create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'qty' => 1,
        'price' => 20000,
        'discount_price' => 0,
    ]);

    actingAs($user);
    Filament::setCurrentPanel(Filament::getPanel('tenant'));

    return [$user, $product];
}

test('the web cashier rejects a payment method that does not exist instead of creating one', function () {
    cashierWithCart();
    $methods = PaymentMethod::count();

    Livewire::test(Cashier::class)
        ->set('cartDetail.payed_money', 20000)
        ->set('cartDetail.payment_method_id', 999999)
        ->set('cartDetail.discount_price', '0')
        ->call('proceedThePayment')
        ->assertHasErrors(['payment_method_id']);

    expect(PaymentMethod::count())->toBe($methods)
        ->and(Selling::count())->toBe(0);
});

test('the web cashier needs a member and a due date for a credit sale', function () {
    cashierWithCart();
    $credit = PaymentMethod::create([
        'name' => 'Credit',
        'is_cash' => false,
        'is_debit' => false,
        'is_credit' => true,
        'is_wallet' => false,
    ]);

    Livewire::test(Cashier::class)
        ->set('cartDetail.payment_method_id', $credit->id)
        ->set('cartDetail.discount_price', '0')
        ->call('proceedThePayment')
        ->assertHasErrors(['member_id', 'due_date']);

    expect(Selling::count())->toBe(0);
});

test('the web cashier can sell on credit to a member', function () {
    cashierWithCart();
    $member = Member::factory()->create();
    $credit = PaymentMethod::create([
        'name' => 'Credit',
        'is_cash' => false,
        'is_debit' => false,
        'is_credit' => true,
        'is_wallet' => false,
    ]);

    Livewire::test(Cashier::class)
        ->set('cartDetail.payment_method_id', $credit->id)
        ->set('cartDetail.member_id', $member->id)
        ->set('cartDetail.due_date', now()->addMonth()->format('Y-m-d'))
        ->set('cartDetail.discount_price', '0')
        ->call('proceedThePayment')
        ->assertHasNoErrors();

    expect(Selling::count())->toBe(1);
});

test('the web cashier applies the same checks as the API: an invalid voucher is rejected', function () {
    cashierWithCart();

    Livewire::test(Cashier::class)
        ->set('cartDetail.payed_money', 20000)
        ->set('cartDetail.payment_method_id', 1)
        ->set('cartDetail.discount_price', '0')
        ->set('cartDetail.voucher', 'NOPE')
        ->call('proceedThePayment')
        ->assertHasErrors(['voucher']);

    expect(Selling::count())->toBe(0);
});

test('the web cashier applies the same checks as the API: discounts need the discount feature', function () {
    Feature::deactivate(Discount::class);
    cashierWithCart();

    Livewire::test(Cashier::class)
        ->set('cartDetail.payed_money', 20000)
        ->set('cartDetail.payment_method_id', 1)
        ->set('cartDetail.discount_price', '1000')
        ->call('proceedThePayment')
        ->assertHasErrors(['discount_price']);

    expect(Selling::count())->toBe(0);
});

test('the web cashier cannot sell while the cash drawer is enabled but not opened', function () {
    cashierWithCart();
    Setting::set('cash_drawer_enabled', true);

    Livewire::test(Cashier::class)
        ->set('cartDetail.payed_money', 20000)
        ->set('cartDetail.payment_method_id', 1)
        ->set('cartDetail.discount_price', '0')
        ->call('proceedThePayment')
        ->assertHasErrors(['cash_drawer']);

    expect(Selling::count())->toBe(0);
});
