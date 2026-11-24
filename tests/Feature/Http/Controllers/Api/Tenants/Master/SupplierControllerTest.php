<?php

use App\Models\Tenants\Supplier;
use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;

uses(RefreshDatabaseWithTenant::class);

test('the supplier list answers with the shops suppliers', function () {
    Supplier::create(['name' => 'PT Sumber']);

    $response = actingAs(User::first())->getJson('/api/master/supplier')->assertOk();

    // A simplePaginate response keeps the rows under `data`.
    expect(collect($response->json('data'))->pluck('name'))->toContain('PT Sumber');
});

test('a supplier can be created, updated and removed', function () {
    $owner = User::first();

    actingAs($owner)
        ->postJson('/api/master/supplier', ['name' => 'CV Maju', 'email' => 'maju@mail.com', 'phone_number' => '0811'])
        ->assertOk();

    $supplier = Supplier::where('name', 'CV Maju')->first();
    expect($supplier)->not->toBeNull();

    actingAs($owner)
        ->putJson("/api/master/supplier/{$supplier->id}", ['name' => 'CV Maju Jaya'])
        ->assertOk();

    expect($supplier->fresh()->name)->toBe('CV Maju Jaya');

    actingAs($owner)->deleteJson("/api/master/supplier/{$supplier->id}")->assertOk();

    expect(Supplier::where('name', 'CV Maju Jaya')->exists())->toBeFalse();
});

test('a duplicate phone number is a 422, not a 500', function () {
    Supplier::create(['name' => 'PT Sumber', 'phone_number' => '0811']);
    $owner = User::first();

    actingAs($owner)
        ->postJson('/api/master/supplier', ['name' => 'CV Maju', 'phone_number' => '0811'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('phone_number');
});
