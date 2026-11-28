<?php

use App\Models\Company;
use App\Models\Tenants\Category;
use App\Models\Tenants\PaymentMethod;
use App\Models\Tenants\User;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function () {
    config(['license.provision_secret' => 'test-secret']);
});

function provisionPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Toko Cloud',
        'owner_email' => 'cloud@lakasir.test',
        'owner_name' => 'Cloud Owner',
        'owner_password' => 'password123',
        'trial_days' => 30,
    ], $overrides);
}

test('a cloud call creates a fully provisioned shop', function () {
    $this->postJson('/api/cloud/provision', provisionPayload(), ['X-Provision-Secret' => 'test-secret'])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Toko Cloud');

    $company = Company::where('name', 'Toko Cloud')->firstOrFail();
    $company->makeCurrent();

    expect($company->provisioned_by)->toBe('cloud')
        ->and($company->onTrial())->toBeTrue()
        ->and(PaymentMethod::count())->toBeGreaterThan(0)
        ->and(Category::count())->toBeGreaterThan(0);

    $owner = User::where('email', 'cloud@lakasir.test')->firstOrFail();

    expect($owner->companies()->whereKey($company->id)->exists())->toBeTrue();
});

test('a wrong or missing secret is refused', function () {
    $this->postJson('/api/cloud/provision', provisionPayload(), ['X-Provision-Secret' => 'wrong'])
        ->assertForbidden();

    $this->postJson('/api/cloud/provision', provisionPayload())
        ->assertForbidden();

    expect(Company::where('name', 'Toko Cloud')->exists())->toBeFalse();
});

test('without a configured secret the endpoint does not exist', function () {
    config(['license.provision_secret' => null]);

    $this->postJson('/api/cloud/provision', provisionPayload(), ['X-Provision-Secret' => 'anything'])
        ->assertNotFound();
});

test('a retried call does not make a second shop', function () {
    $this->postJson('/api/cloud/provision', provisionPayload(), ['X-Provision-Secret' => 'test-secret'])
        ->assertCreated();

    $this->postJson('/api/cloud/provision', provisionPayload(), ['X-Provision-Secret' => 'test-secret'])
        ->assertOk();

    expect(Company::where('name', 'Toko Cloud')->count())->toBe(1);
});

test('a cloud shop without trial_days has no trial, like a local one', function () {
    $payload = provisionPayload();
    unset($payload['trial_days']);

    $this->postJson('/api/cloud/provision', $payload, ['X-Provision-Secret' => 'test-secret'])
        ->assertCreated();

    expect(Company::where('name', 'Toko Cloud')->firstOrFail()->trial_ends_at)->toBeNull();
});
