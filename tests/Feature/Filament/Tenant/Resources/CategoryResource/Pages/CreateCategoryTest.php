<?php

use App\Filament\Tenant\Resources\CategoryResource\Pages\CreateCategory;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('create category page can be rendered', function () {
    $user = User::factory()->create();

    $user->givePermissionTo(['create category', 'read category']);
    $user->save();

    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(CreateCategory::class)
        ->assertOk();
});
