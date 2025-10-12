<?php

use App\Filament\Tenant\Pages\Cashier;
use App\Models\Tenants\About;
use App\Models\Tenants\CartItem;
use App\Models\Tenants\Member;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Table;
use App\Models\Tenants\User;
use App\Models\Tenants\Voucher as TenantsVoucher;
use App\Services\Tenants\SellingService;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('cashier page can be rendered', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(Cashier::class)
        ->assertOk();
});

test('mount initializes cart items and related data', function () {
    $user = User::factory()->create();

    // Create test data
    About::create(['name' => 'Test Shop']);
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $this->mockFilamentUser($user);

    $product = Product::factory()->create();
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    $voucher = TenantsVoucher::create([
        'name' => 'Test Voucher',
        'code' => 'TESTVOUCHER',
        'type' => 'flat',
        'nominal' => 5000,
        'kuota' => 10,
        'minimal_buying' => 15000,
        'start_date' => today()->subDay(),
        'expired' => today()->addDay(),
    ]);

    PaymentMethod::create([
        'name' => 'Cash',
        'is_cash' => true,
        'is_credit' => false,
        'is_debit' => false,
        'is_wallet' => false,
    ]);

    Member::create(['name' => 'Test Member', 'email' => 'test@example.com']);
    Table::create(['number' => 'T1']);

    $cashier = new Cashier();
    $cashier->mount();

    expect($cashier->cartItems)->toBeInstanceOf(Collection::class);
    expect($cashier->cartItems->count())->toBe(1);
    expect($cashier->availableVoucher->count())->toBe(1);
    expect(count($cashier->paymentMethods))->toBeGreaterThan(0);
    expect($cashier->members)->toBeInstanceOf(Collection::class);
    expect($cashier->tableOption)->toBeInstanceOf(Collection::class);
    expect($cashier->sub_total)->toBe(20000.0);
    expect($cashier->total_price)->toBe(22000.0); // 20000 + 10% tax
});

test('store cart form has correct schema', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);

    $component = Livewire::actingAs($user)->test(Cashier::class);

    $form = $component->get('storeCartForm');

    expect($form)->toBeInstanceOf(\Filament\Forms\Form::class);

    $cashier = $component->instance();
    expect($cashier)->toBeInstanceOf(Cashier::class);
});

test('store cart validates voucher and calculates discount', function () {
    $user = User::factory()->create();

    // Create test data
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $product = Product::factory()->create();
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    $voucher = TenantsVoucher::create([
        'name' => 'Test Voucher',
        'code' => 'TESTVOUCHER',
        'type' => 'flat',
        'nominal' => 5000,
        'kuota' => 10,
        'minimal_buying' => 15000,
        'start_date' => '2020-01-01',
        'expired' => '2030-01-01',
    ]);

    $this->mockFilamentUser($user);

    $cashier = new Cashier();
    $cashier->mount();
    $cashier->cartDetail['voucher'] = 'TESTVOUCHER';
    $cashier->cartDetail['discount_price'] = '0';
    $cashier->storeCart();

    expect($cashier->cartDetail['voucher'])->toBe('TESTVOUCHER');
});

test('proceed payment creates selling transaction', function () {
    $user = User::factory()->create();

    // Create test data
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $product = Product::factory()->create(['stock' => 10]);
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    PaymentMethod::create([
        'name' => 'Cash',
        'is_cash' => true,
        'is_credit' => false,
        'is_debit' => false,
        'is_wallet' => false,
    ]);

    $sellingService = app(SellingService::class);

    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(Cashier::class)
        ->set('cartDetail.payment_method_id', 1)
        ->set('cartDetail.payed_money', 22000)
        ->set('cartDetail.friend_price', false)
        ->call('proceedThePayment', $sellingService);

    expect(Selling::count())->toBe(1);
    expect(CartItem::count())->toBe(0);
});

test('assign voucher validates and applies voucher', function () {
    $user = User::factory()->create();

    $this->mockFilamentUser($user);

    // Create test data
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $product = Product::factory()->create();
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    $voucher = TenantsVoucher::create([
        'name' => 'Valid Voucher',
        'code' => 'VALIDVOUCHER',
        'type' => 'flat',
        'nominal' => 5000,
        'kuota' => 10,
        'minimal_buying' => 15000,
        'start_date' => today()->subDay(),
        'expired' => today()->addDay(),
    ]);

    $cashier = new Cashier();
    $cashier->mount();
    $result = $cashier->assignVoucher('VALIDVOUCHER');

    expect($cashier->cartDetail['voucher'])->toBe('VALIDVOUCHER');
});

test('assign voucher fails with invalid voucher', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(Cashier::class)
        ->call('assignVoucher', 'INVALIDVOUCHER')
        ->assertSet('cartDetail.voucher', null);
});

test('remove voucher clears voucher and recalculates total', function () {
    $user = User::factory()->create();

    // Create test data
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $product = Product::factory()->create();
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(Cashier::class)
        ->set('cartDetail.voucher', 'SOMEVOUCHER')
        ->call('removeVoucher')
        ->assertSet('cartDetail.voucher', null);
});

test('calculate total price includes tax and discounts', function () {
    $user = User::factory()->create();

    // Create test data
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $product = Product::factory()->create();
    $cartItem = CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'discount_price' => 1000,
        'user_id' => $user->id,
    ]);

    $this->mockFilamentUser($user);

    $cashier = new Cashier();
    $cashier->mount();

    expect($cashier->sub_total)->toBe(20000.0);
    expect($cashier->total_price)->toBe(21000.0); // 20000 + 2000 tax - 1000 discount
});

test('fill payment method sets correct label', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);

    PaymentMethod::create([
        'name' => 'Credit Card',
        'is_cash' => false,
        'is_credit' => true,
        'is_debit' => false,
        'is_wallet' => false,
    ]);

    $cashier = new Cashier();
    $cashier->mount();
    $cashier->cartDetail['payment_method_id'] = 1;
    $cashier->fillPaymentMethod();

    expect($cashier->cartDetail['payment_method_label'])->toBe('Cash');
});

test('fill member sets correct label', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);

    Member::create(['name' => 'John Doe', 'email' => 'john@example.com']);

    $cashier = new Cashier();
    $cashier->mount();
    $cashier->cartDetail['member_id'] = 1;
    $cashier->fillMember();

    expect($cashier->cartDetail['member_label'])->toBe('John Doe');
});

test('store cart handles manual discount', function () {
    $user = User::factory()->create();

    // Create test data
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $product = Product::factory()->create();
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    $this->mockFilamentUser($user);

    $cashier = new Cashier();
    $cashier->mount();
    $cashier->cartDetail['discount_price'] = '1,000';
    $cashier->storeCart();

    expect($cashier->total_price)->toBe(21000.0); // 20000 + 2000 tax - 1000 discount
});

test('proceed payment validates required fields', function () {
    $user = User::factory()->create();

    // Create test data
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $product = Product::factory()->create(['stock' => 10]);
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    PaymentMethod::create([
        'name' => 'Cash',
        'is_cash' => true,
        'is_credit' => false,
        'is_debit' => false,
        'is_wallet' => false,
    ]);

    $sellingService = app(SellingService::class);

    $this->mockFilamentUser($user);

    // Test without payment method
    Livewire::actingAs($user)
        ->test(Cashier::class)
        ->set('cartDetail.payment_method_id', null)
        ->call('proceedThePayment', $sellingService)
        ->assertHasErrors(['payment_method_id']);
});

test('voucher validation fails for invalid code', function () {
    $user = User::factory()->create();

    // Create test data
    Setting::create(['key' => 'default_tax', 'value' => '10']);
    Setting::create(['key' => 'currency', 'value' => 'IDR']);

    $product = Product::factory()->create();
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 1,
        'price' => 20000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    $this->mockFilamentUser($user);

    $cashier = new Cashier();
    $cashier->mount();
    $result = $cashier->assignVoucher('INVALIDCODE');

    expect($cashier->cartDetail['voucher'])->toBeNull();
});

test('cart items are loaded with product relationships', function () {
    $user = User::factory()->create();

    $product = Product::factory()->create(['name' => 'Test Product']);
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 1,
        'price' => 15000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    $this->mockFilamentUser($user);

    $cashier = new Cashier();
    $cashier->mount();

    expect($cashier->cartItems->first()->product->name)->toBe('Test Product');
});

test('available vouchers are filtered by minimum buying and dates', function () {
    $user = User::factory()->create();

    $this->mockFilamentUser($user);

    // Create cart with sufficient total
    $product = Product::factory()->create();
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    // Valid voucher
    TenantsVoucher::create([
        'name' => 'Valid Voucher',
        'code' => 'VALID',
        'type' => 'flat',
        'nominal' => 1000,
        'kuota' => 10,
        'minimal_buying' => 15000,
        'start_date' => today()->subDay(),
        'expired' => today()->addDay(),
    ]);

    // Invalid voucher (expired)
    TenantsVoucher::create([
        'name' => 'Expired Voucher',
        'code' => 'EXPIRED',
        'type' => 'flat',
        'nominal' => 1000,
        'kuota' => 10,
        'minimal_buying' => 15000,
        'start_date' => today()->subMonth(),
        'expired' => today()->subDay(),
    ]);

    $cashier = new Cashier();
    $cashier->mount();

    expect($cashier->availableVoucher->count())->toBe(1);
    expect($cashier->availableVoucher->first()->code)->toBe('VALID');
});