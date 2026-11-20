<?php

use App\Features\Discount;
use App\Filament\Tenant\Pages\Cashier;
use App\Models\Tenants\CartItem;
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
