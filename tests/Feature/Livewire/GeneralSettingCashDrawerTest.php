<?php

use App\Filament\Tenant\Pages\GeneralSetting;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('general setting persists the cash drawer toggle', function () {
    Setting::set('selling_method', 'fifo');
    Cache::clear();
    $user = User::first();
    (new Profile)->forceFill([
        'user_id' => $user->id,
        'phone' => '1',
        'address' => 'a',
        'locale' => 'en',
        'timezone' => 'UTC',
    ])->save();
    actingAs($user);
    Filament::setCurrentPanel(Filament::getPanel('tenant'));

    Livewire::test(GeneralSetting::class)
        ->assertOk()
        ->set('setting.cash_drawer_enabled', true)
        ->call('saveApp');

    expect((bool) Setting::get('cash_drawer_enabled'))->toBeTrue();
});
