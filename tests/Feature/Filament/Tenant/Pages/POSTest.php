<?php

use App\Filament\Tenant\Pages\POS;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('pos page can be rendered', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(POS::class)
        ->assertOk();
});