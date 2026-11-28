<?php

use App\Filament\Tenant\Pages\Cashier;
use App\Filament\Tenant\Pages\GeneralSetting;
use App\Models\Company;
use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    Setting::set('selling_method', 'fifo');
    Setting::set('cash_drawer_enabled', true);
    Cache::clear();

    $this->user = User::factory()->create();
    $company = Company::currentId();
    $this->user->companies()->attach($company);
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    Cache::clear();

    actingAs($this->user);
    Filament::setCurrentPanel(Filament::getPanel('tenant'));
});

function grant(array $permissions): void
{
    test()->user->givePermissionTo($permissions);
    app(PermissionRegistrar::class)->forgetCachedPermissions();
}

test('a cashier without the open permission cannot open the drawer', function () {
    grant(['create selling']);

    Livewire::test(Cashier::class)
        ->set('cashDrawerCash', 100000)
        ->call('openCashDrawer');

    expect(CashDrawer::count())->toBe(0);
});

test('a cashier without the close permission cannot close the drawer', function () {
    grant(['create selling', 'open cash drawer']);
    CashDrawer::create(['cash' => 100000, 'opened_by' => $this->user->id]);

    Livewire::test(Cashier::class)
        ->set('countedCash', 100000)
        ->call('closeCashDrawer');

    expect(CashDrawer::lastOpened()->first()->closed_by)->toBeNull();
});

test('a cashier with the permissions can open and close', function () {
    grant(['create selling', 'open cash drawer', 'close cash drawer']);

    Livewire::test(Cashier::class)
        ->set('cashDrawerCash', 100000)
        ->call('openCashDrawer')
        ->assertSet('cashDrawerOpen', true)
        ->set('countedCash', 100000)
        ->call('closeCashDrawer');

    expect(CashDrawer::latest('id')->first()->closed_by)->toBe($this->user->id);
});

test('a user without the enable permission cannot turn the drawer on through saveApp', function () {
    (new Profile)->forceFill([
        'user_id' => $this->user->id,
        'phone' => '1',
        'address' => 'a',
        'locale' => 'en',
        'timezone' => 'UTC',
    ])->save();

    Setting::set('cash_drawer_enabled', false);
    grant(['access general setting']);

    Livewire::test(GeneralSetting::class)
        ->assertOk()
        ->set('setting.cash_drawer_enabled', true)
        ->call('saveApp');

    expect((bool) Setting::get('cash_drawer_enabled'))->toBeFalse();
});

test('the summary is reachable with only the close permission', function () {
    grant(['create selling', 'close cash drawer']);
    CashDrawer::create(['cash' => 100000, 'opened_by' => $this->user->id]);

    Livewire::test(Cashier::class)
        ->assertOk()
        ->assertSee(__('Cash drawer summary'));
});
