<?php

use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\getJson;
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

test('api logout revokes the current access token', function () {
    $user = User::first();
    $token = postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertOk()->json('data.token');

    // A mobile client keeps no session between requests; drop the one the login request left behind.
    $this->flushSession();
    app('auth')->forgetGuards();

    postJson('/api/auth/logout', [], ['Authorization' => "Bearer {$token}"])
        ->assertNoContent();

    expect($user->tokens()->count())->toBe(0);

    app('auth')->forgetGuards();

    getJson('/api/auth/me', ['Authorization' => "Bearer {$token}"])
        ->assertUnauthorized();
});

test('api logout only revokes the token used for the request', function () {
    $user = User::first();
    $first = $user->createToken('first')->plainTextToken;
    $user->createToken('second');

    postJson('/api/auth/logout', [], ['Authorization' => "Bearer {$first}"])
        ->assertNoContent();

    expect($user->tokens()->pluck('name')->all())->toBe(['second']);
});
