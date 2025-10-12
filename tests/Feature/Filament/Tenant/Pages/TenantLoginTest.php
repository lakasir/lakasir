<?php

use App\Filament\Tenant\Pages\TenantLogin;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('tenantlogin page can be rendered', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user, false);

    Livewire::actingAs($user)
        ->test(TenantLogin::class)
        ->assertOk();
});