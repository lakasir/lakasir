<?php

use App\Models\Company;
use Tests\RefreshDatabaseWithTenant;

use function Pest\Laravel\getJson;

uses(RefreshDatabaseWithTenant::class);

test('the domain check answers with the shop id, and nothing else about the shop', function () {
    $response = getJson('/api/check')->assertOk();

    expect($response->json())->toHaveKeys(['tenant'])
        ->and($response->json())->not->toHaveKey('tenant_email')
        ->and($response->json('tenant'))->toBe(Company::currentId());
});

test('there is no user or framework-version endpoint', function (string $url) {
    getJson($url)->assertNotFound();
})->with(['/api/user', '/api']);
