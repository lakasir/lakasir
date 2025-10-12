<?php

use App\Filament\Tenant\Pages\CartItem;
use App\Models\Tenants\User;
use Livewire\Livewire;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

test('cartitem page can be rendered', function () {
    $user = User::factory()->create();

    $this->mockFilamentUser($user);

    Livewire::actingAs($user)
        ->test(CartItem::class)
        ->assertOk();
});

test('mount initializes cart items', function () {
    $user = User::factory()->create();

    $this->mockFilamentUser($user);

    $cartItem = new CartItem();
    $cartItem->mount();

    expect($cartItem->cartItems)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
