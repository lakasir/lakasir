<?php

use App\Filament\Tenant\Pages\Cashier;
use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Setting::set('selling_method', 'fifo');
    Setting::set('cash_drawer_enabled', true);
    Cache::clear();
    $this->user = User::first();
    $this->product = Product::factory()->create([
        'initial_price' => 10000,
        'selling_price' => 20000,
        'stock' => 10,
    ]);
    actingAs($this->user);
    Filament::setCurrentPanel(Filament::getPanel('tenant'));
});

test('the summary expects the opening cash plus cash sales', function () {
    $cash = PaymentMethod::where('name', 'Cash')->first();

    Livewire::test(Cashier::class)
        ->assertSet('cashDrawerOpen', false)
        ->set('cashDrawerCash', 100000)
        ->call('openCashDrawer')
        ->assertSet('cashDrawerOpen', true)
        ->assertSet('cashDrawerSummary.opening_cash', 100000.0);

    expect(CashDrawer::lastOpened()->first()->opened_by)->toBe($this->user->id);

    Selling::factory()->create([
        'code' => 'SELL9999',
        'payment_method_id' => $cash->id,
        'cash_drawer_id' => CashDrawer::lastOpened()->first()->id,
        'payed_money' => 50000,
        'money_changes' => 30000,
        'total_price' => 20000,
        'total_qty' => 1,
        'is_paid' => true,
    ]);

    Livewire::test(Cashier::class)
        ->call('refreshCashDrawer')
        ->assertSet('cashDrawerSummary.cash_sales', 20000.0)
        ->assertSet('cashDrawerSummary.expected_cash', 120000.0);
});

test('the save button in the open drawer modal is wired to the action', function () {
    $view = file_get_contents(resource_path('views/filament/tenant/pages/cashier.blade.php'));
    $modal = substr($view, strpos($view, 'id="open-cash-drawer"'));
    $modal = substr($modal, 0, strpos($modal, '</x-filament::modal>'));

    expect($modal)
        ->toContain('wire:click="openCashDrawer"')
        ->not->toContain('type="submit"');
});

test('opening the drawer twice is rejected', function () {
    Livewire::test(Cashier::class)
        ->set('cashDrawerCash', 100000)
        ->call('openCashDrawer')
        ->set('cashDrawerCash', 5000)
        ->call('openCashDrawer');

    expect(CashDrawer::count())->toBe(1)
        ->and((float) CashDrawer::lastOpened()->first()->cash)->toBe(100000.0);
});

test('closing the drawer stores the counted cash and shows the difference', function () {
    Livewire::test(Cashier::class)
        ->set('cashDrawerCash', 100000)
        ->call('openCashDrawer')
        ->set('countedCash', 120000)
        ->call('closeCashDrawer')
        ->assertSet('cashDrawerOpen', false)
        ->assertSet('cashDrawerSummary.counted_cash', 120000.0)
        ->assertSet('cashDrawerSummary.difference', 20000.0);

    $drawer = CashDrawer::latest('id')->first();
    expect($drawer->closed_by)->toBe($this->user->id)
        ->and($drawer->closed_at)->not->toBeNull();
});

test('the cashier refuses to check out while the drawer is not open', function () {
    Livewire::test(Cashier::class)
        ->assertSet('cashDrawerEnabled', true)
        ->assertSet('cashDrawerOpen', false)
        ->set('cartDetail.payed_money', 20000)
        ->set('cartDetail.payment_method_id', PaymentMethod::first()->id)
        ->set('cartDetail.discount_price', '0')
        ->call('proceedThePayment');

    expect(Selling::count())->toBe(0);
});
