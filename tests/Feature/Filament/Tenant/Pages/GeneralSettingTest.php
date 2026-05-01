<?php

use App\Filament\Tenant\Pages\GeneralSetting;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('general setting page can be rendered', function () {
    $user = User::factory()->create();
    $user->profile()->create([
        'phone' => '123456789',
        'address' => 'Test Address',
        'locale' => 'en',
        'timezone' => 'UTC',
    ]);

    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(GeneralSetting::class)
        ->assertOk();
});

test('mount initializes data', function () {
    $user = User::factory()->create();
    $user->profile()->create([
        'phone' => '123456789',
        'address' => 'Test Address',
        'locale' => 'en',
        'timezone' => 'UTC',
    ]);

    $this->mockFilamentUser($user);
    \Illuminate\Support\Facades\Auth::shouldReceive('user')->andReturn($user);

    $page = new GeneralSetting();
    $page->mount();

    expect($page->about)->toBeArray();
    expect($page->setting)->toBeArray();
    expect($page->feature)->toBeArray();
    expect($page->profile)->toBeArray();
});