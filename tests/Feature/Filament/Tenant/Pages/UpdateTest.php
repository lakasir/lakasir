<?php

use App\Filament\Tenant\Pages\Update;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('update page can be rendered', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(Update::class)
        ->assertOk();
});