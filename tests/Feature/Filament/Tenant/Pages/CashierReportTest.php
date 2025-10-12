<?php

use App\Filament\Tenant\Pages\CashierReport;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('cashierreport page can be rendered', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(CashierReport::class)
        ->assertOk();
});
