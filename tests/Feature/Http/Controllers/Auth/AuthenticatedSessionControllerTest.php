<?php

use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\postJson;

uses(RefreshDatabaseWithTenant::class);

test('user can login with valid credentials', function () {
    $user = User::first();

    $response = postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertSee('token');
});
