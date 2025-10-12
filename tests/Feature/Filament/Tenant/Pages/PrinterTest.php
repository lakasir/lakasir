<?php

use App\Filament\Tenant\Pages\Printer;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('printer page can be rendered', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(Printer::class)
        ->assertOk();
});