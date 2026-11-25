<?php

use App\Models\License;
use App\Services\License\CloudClient;
use App\Services\License\Fingerprint;
use App\Services\License\License as LicenseGate;
use Illuminate\Support\Facades\Http;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

/**
 * A token this install would accept: signed with the configured public key.
 */
function signedFor(string $publicKey, array $claims): string
{
    $keypairEnv = base64_decode($publicKey, true);
    $json = json_encode($claims + ['v' => 1, 'iat' => now()->timestamp, 'exp' => now()->addDays(7)->timestamp], JSON_UNESCAPED_SLASHES);
    $signature = sodium_crypto_sign_detached($json, testSecretKey());

    return base64_encode($json).'.'.base64_encode($signature);
}

function testKeypair(): array
{
    static $pair = null;

    if ($pair === null) {
        $k = sodium_crypto_sign_keypair();
        $pair = [base64_encode(sodium_crypto_sign_publickey($k)), base64_encode(sodium_crypto_sign_secretkey($k))];
    }

    return $pair;
}

function testSecretKey(): string
{
    return base64_decode(testKeypair()[1], true);
}

beforeEach(function () {
    License::current()->update(['key' => null, 'token' => null, 'last_refreshed_at' => null, 'last_refresh_error' => null]);

    config([
        'license.public_key' => testKeypair()[0],
        'license.cloud_url' => 'https://cloud.test',
        'license.grace_days' => 30,
    ]);
});

test('the fingerprint is stable and does not depend on the shop name', function () {
    $first = Fingerprint::make();

    expect(Fingerprint::make())->toBe($first)
        ->and(Fingerprint::short())->toHaveLength(12)
        ->and($first)->toStartWith('sha256:');
});

test('refreshing stores the token the cloud returned', function () {
    License::putKey('LKS-TEST-KEY');

    $token = signedFor(testKeypair()[0], ['plan' => 'pro', 'limits' => ['users' => 5], 'features' => ['purchasing']]);

    Http::fake([
        'cloud.test/api/license/refresh' => Http::response(['success' => true, 'data' => ['token' => $token]]),
    ]);

    $result = app(CloudClient::class)->refresh();

    expect($result['ok'])->toBeTrue()
        ->and(app(LicenseGate::class)->plan())->toBe('pro')
        ->and(License::current()->last_refreshed_at)->not->toBeNull();

    Http::assertSent(fn ($request) => $request['key'] === 'LKS-TEST-KEY'
        && $request['fingerprint'] === Fingerprint::make());
});

test('a token signed by the wrong key never replaces a working one', function () {
    License::putKey('LKS-TEST-KEY');

    $good = signedFor(testKeypair()[0], ['plan' => 'pro']);

    $otherKeypair = sodium_crypto_sign_keypair();
    $payload = json_encode(['plan' => 'enterprise']);
    $forged = base64_encode($payload).'.'.base64_encode(
        sodium_crypto_sign_detached($payload, sodium_crypto_sign_secretkey($otherKeypair))
    );

    // One fake, two responses in order: the good one, then the forged one.
    Http::fake([
        'cloud.test/*' => Http::sequence()
            ->push(['data' => ['token' => $good]])
            ->push(['data' => ['token' => $forged]]),
    ]);

    app(CloudClient::class)->refresh();
    $before = License::token();

    $result = app(CloudClient::class)->refresh();

    expect($result['ok'])->toBeFalse()
        ->and(License::token())->toBe($before);
});

test('an unreachable cloud keeps the old token', function () {
    License::putKey('LKS-TEST-KEY');

    $good = signedFor(testKeypair()[0], ['plan' => 'pro']);

    Http::fake([
        'cloud.test/*' => Http::sequence()
            ->push(['data' => ['token' => $good]])
            ->push(['message' => 'down'], 503),
    ]);

    app(CloudClient::class)->refresh();
    $before = License::token();

    $result = app(CloudClient::class)->refresh();

    expect($result['ok'])->toBeFalse()
        ->and(License::token())->toBe($before);
});

test('the command records a failure without losing the token', function () {
    License::putKey('LKS-TEST-KEY');
    Http::fake(['cloud.test/*' => Http::response(['message' => 'Subscription is not active'], 403)]);

    $this->artisan('license:refresh')->assertFailed();

    expect(License::current()->last_refresh_error)->toBe('Subscription is not active');
});

test('activating stores the key it was given', function () {
    $token = signedFor(testKeypair()[0], ['plan' => 'pro']);
    Http::fake(['cloud.test/api/license/activate' => Http::response(['data' => ['token' => $token]])]);

    app(CloudClient::class)->activate('LKS-NEW-KEY');

    expect(License::key())->toBe('LKS-NEW-KEY');
});

test('without a cloud url nothing is attempted', function () {
    config(['license.cloud_url' => null]);
    Http::fake();

    expect(app(CloudClient::class)->configured())->toBeFalse()
        ->and(app(CloudClient::class)->refresh()['ok'])->toBeFalse();

    Http::assertNothingSent();
});
