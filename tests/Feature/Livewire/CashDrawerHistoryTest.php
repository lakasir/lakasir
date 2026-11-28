<?php

use App\Filament\Tenant\Resources\CashDrawerResource\Pages\ListCashDrawers;
use App\Filament\Tenant\Resources\CashDrawerResource\Pages\ViewCashDrawer;
use App\Models\Company;
use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\PaymentMethod;
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
    actingAs($this->user);
    Filament::setCurrentPanel(Filament::getPanel('tenant'));
});

function shiftWithSale(User $user, float $openingCash, float $paid, float $change): CashDrawer
{
    $drawer = CashDrawer::create(['cash' => $openingCash, 'opened_by' => $user->id]);

    Selling::factory()->create([
        'code' => 'SELL'.rand(1000, 9999),
        'payment_method_id' => PaymentMethod::where('name', 'Cash')->first()->id,
        'cash_drawer_id' => $drawer->id,
        'payed_money' => $paid,
        'money_changes' => $change,
        'total_price' => $paid - $change,
        'total_qty' => 1,
        'is_paid' => true,
    ]);

    return $drawer;
}

test('the cash drawer history lists every shift', function () {
    $open = shiftWithSale($this->user, 100000, 50000, 30000);
    $closed = shiftWithSale($this->user, 0, 20000, 0);
    $closed->update([
        'closed_by' => $this->user->id,
        'closed_at' => now(),
        'counted_cash' => 20000,
    ]);

    Livewire::test(ListCashDrawers::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$open, $closed]);
});

test('a shift shows the expected cash, the counted cash and the difference', function () {
    $drawer = shiftWithSale($this->user, 100000, 50000, 30000);
    $drawer->update([
        'closed_by' => $this->user->id,
        'closed_at' => now(),
        'counted_cash' => 100000,
    ]);

    Livewire::test(ViewCashDrawer::class, ['record' => $drawer->getRouteKey()])
        ->assertOk()
        ->assertSee('120,000')
        ->assertSee('100,000');
});

test('a shift from another company is not listed', function () {
    $mine = shiftWithSale($this->user, 100000, 0, 0);
    $mine->company->makeCurrent();

    $otherCompany = Company::create(['name' => 'Other Shop']);
    $otherCompany->makeCurrent();
    $foreign = CashDrawer::create(['cash' => 5000, 'opened_by' => User::factory()->create()->id]);

    $mine->company->makeCurrent();

    Livewire::test(ListCashDrawers::class)
        ->assertCanSeeTableRecords([$mine])
        ->assertCanNotSeeTableRecords([$foreign]);
});
