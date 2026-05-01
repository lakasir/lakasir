<?php

use App\Filament\Tenant\Pages\SellingReport;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('sellingreport page can be rendered', function () {
    $user = User::factory()->create();
    $this->mockFilamentUser($user);    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(SellingReport::class)
        ->assertOk();
});