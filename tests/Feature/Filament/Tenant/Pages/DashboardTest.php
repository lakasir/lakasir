<?php

use App\Filament\Tenant\Pages\Dashboard;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('dashboard page can be rendered', function () {
    $user = User::factory()->create();

    $this->mockFilamentUser($user);
    \Filament\Facades\Filament::shouldReceive('getWidgets')->andReturn([]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->assertOk();
});