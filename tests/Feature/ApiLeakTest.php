<?php

use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

uses(RefreshDatabaseWithTenant::class);

test('the domain check answers with the tenant id, and nothing else about the shop', function () {
    $response = getJson('/api/check')->assertOk();

    expect($response->json())->toHaveKeys(['tenant'])
        ->and($response->json())->not->toHaveKey('tenant_email')
        ->and($response->json('tenant'))->toBe(tenant('id'));
});

test('there is no user or framework-version endpoint', function (string $url) {
    getJson($url)->assertNotFound();
})->with(['/api/user', '/api']);
