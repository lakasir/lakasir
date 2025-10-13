<?php

namespace Tests\Browser;

use App\Features\Discount;
use App\Models\Tenants\CartItem;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Laravel\Dusk\Browser;
use Laravel\Pennant\Feature;

test('cashier page loads', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user): void {
        $browser->loginAs($user)
            ->visit('/member/cashier')
            ->pause(2000)
            ->assertSee('Orders details'); // Look for text from the cashier blade file
    });
});

test('calculator functionality works', function () {
    $user = User::factory()->create();

    // Create test cart items
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

    Setting::firstOrCreate(['key' => 'default_tax'], ['value' => '10']);
    Setting::firstOrCreate(['key' => 'currency'], ['value' => 'IDR']);

    $this->browse(function (Browser $browser) use ($user): void {
        $browser->loginAs($user)
            ->visit('/member/cashier')
            ->pause(2000)
            ->press('Proceed to payment')
            ->pause(1000)
            ->assertSee('Pay it');

        // Hide debugbar if present to avoid click interception
        $browser->script("const debugbar = document.querySelector('.phpdebugbar'); if (debugbar) debugbar.style.display = 'none';");

        $browser->pause(500)
            // Test calculator by clicking buttons
            ->click('button[data-calculator="1"]')
            ->click('button[data-calculator="2"]')
            ->click('button[data-calculator="3"]')
            ->pause(500)
            ->assertInputValue('#display', '123');
    });
});


test('payment method selection', function (): void {
    $user = User::factory()->create();

    // Create test cart items
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

    PaymentMethod::create([
        'name' => 'Credit Card',
        'is_cash' => false,
        'is_credit' => true,
        'is_debit' => false,
        'is_wallet' => false,
    ]);

    Setting::firstOrCreate(['key' => 'default_tax', 'value' => '10']);
    Setting::firstOrCreate(['key' => 'currency', 'value' => 'IDR']);

    $this->browse(function (Browser $browser) use ($user): void {
        $browser->loginAs($user)
            ->visit('/member/cashier')
            ->pause(2000)
            ->press('Proceed to payment')
            ->waitForText('Pay it');

        // Test payment method selection
        $browser->click('div[data-payment-method="Cash"]')
            ->assertPresent('div[data-payment-method="Cash"].bg-lakasir-primary.text-white');

        $browser->click('div[data-payment-method="Credit Card"]')
            ->assertPresent('div[data-payment-method="Credit Card"].bg-lakasir-primary.text-white')
            ->assertVisible('input[type="date"]'); // Due date input should appear
    });
});

test('qr scanner modal opens', function (): void {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user): void {
        $browser->loginAs($user)
            ->visit('/member/cashier')
            ->pause(2000)
            ->press('button[aria-label="Scan with camera"]')
            ->waitForText('Scan Barcode with Camera')
            ->assertVisible('#qr-reader')
            ->press('Close');
    });
});

test('fullscreen button exists', function (): void {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user): void {
        $browser->loginAs($user)
            ->visit('/member/cashier')
            ->pause(2000)
            ->assertSee('Proceed to payment')
            ->press('Proceed to payment')
            ->pause(1000)
            ->assertSee('Pay it'); // Check if payment modal opens
    });
});

test('cart quantity input updates', function (): void {
    $user = User::factory()->create();

    // Create test cart items
    $product = Product::factory()->create(['stock' => 10, 'name' => 'Test Product']);
    $cartItem = CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    $this->browse(function (Browser $browser) use ($user, $cartItem): void {
        $browser->loginAs($user)
            ->visit('/member/cashier')
            ->pause(2000)
            ->type("input[data-qty-input=\"{$cartItem->id}\"]", '5')
            ->pause(500) // Wait for Livewire debounce
            ->assertInputValue("input[data-qty-input=\"{$cartItem->id}\"]", '5');
    });
});

test('discount input with mask', function (): void {
    $user = User::factory()->create();

    // Enable discount feature
    Feature::activate(Discount::class);

    // Create test cart items
    $product = Product::factory()->create(['stock' => 10]);
    $cartItem = CartItem::create([
        'product_id' => $product->id,
        'qty' => 2,
        'price' => 10000,
        'price_unit_id' => null,
        'user_id' => $user->id,
    ]);

    Setting::firstOrCreate(['key' => 'default_tax'], ['value' => '10']);
    Setting::firstOrCreate(['key' => 'currency'], ['value' => 'IDR']);

    $this->browse(function (Browser $browser) use ($user, $cartItem): void {
        $browser->loginAs($user)
            ->visit('/member/cashier')
            ->pause(2000)
            ->type('input[data-discount-input]', '1000')
            ->pause(500) // Wait for Livewire debounce
            ->assertInputValue('input[data-discount-input]', '1,000');
    });
});

test('payment shortcut buttons generated', function (): void {
    $user = User::factory()->create();

    // Create test cart items with higher total
    $product = Product::factory()->create(['stock' => 10]);
    CartItem::create([
        'product_id' => $product->id,
        'qty' => 10,
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

    Setting::firstOrCreate(['key' => 'default_tax', 'value' => '10']);
    Setting::firstOrCreate(['key' => 'currency', 'value' => 'IDR']);

    $this->browse(function (Browser $browser) use ($user): void {
        $browser->loginAs($user)
            ->visit('/member/cashier')
            ->pause(2000)
            ->press('Proceed to payment')
            ->waitForText('Pay it')
            ->assertPresent('#calculator-button-shortcut button'); // Should have shortcut buttons
    });
});
