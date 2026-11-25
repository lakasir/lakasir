<?php

use App\Models\Tenants\Setting;
use App\Services\License\License;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

function signClaims(array $claims): array
{
    $keypair = sodium_crypto_sign_keypair();
    $json = json_encode($claims + [
        'v' => 1,
        'iat' => now()->timestamp,
        'exp' => now()->addDay()->timestamp,
    ], JSON_UNESCAPED_SLASHES);

    return [
        base64_encode(sodium_crypto_sign_publickey($keypair)),
        base64_encode($json).'.'.base64_encode(sodium_crypto_sign_detached($json, sodium_crypto_sign_secretkey($keypair))),
    ];
}

beforeEach(function () {
    Setting::set('license.token', '');
});

it('a token signed with the trusted key grants its limits and features', function () {
    [$publicKey, $token] = signClaims([
        'plan' => 'pro',
        'limits' => ['users' => 2, 'products' => null],
        'features' => ['purchasing'],
    ]);

    config(['license.public_key' => $publicKey]);

    $license = app(License::class);
    $license->store($token);

    expect($license->plan())->toBe('pro')
        ->and($license->allows('users', 1))->toBeTrue()
        ->and($license->allows('users', 2))->toBeFalse()
        ->and($license->allows('products', 9999))->toBeTrue()
        ->and($license->allowsFeature('purchasing'))->toBeTrue()
        ->and($license->allowsFeature('voucher'))->toBeFalse()
        ->and($license->licensed())->toBeTrue();
});

it('a token signed with another key is rejected', function () {
    [, $token] = signClaims(['limits' => ['users' => 100]]);
    [$otherPublicKey] = signClaims(['limits' => ['users' => 100]]);

    config(['license.public_key' => $otherPublicKey]);

    $license = app(License::class);
    $license->store($token);

    expect($license->licensed())->toBeFalse()
        ->and($license->plan())->toBe('free');
});

it('a tampered payload is rejected', function () {
    [$publicKey, $token] = signClaims(['limits' => ['users' => 1]]);

    config(['license.public_key' => $publicKey]);

    [$payload, $signature] = explode('.', $token);

    $forged = json_decode(base64_decode($payload), true);
    $forged['limits']['users'] = 99999;

    $license = app(License::class);
    $license->store(base64_encode(json_encode($forged)).'.'.$signature);

    expect($license->licensed())->toBeFalse();
});

it('an expired token still works inside the grace period and stops after it', function () {
    [$publicKey, $token] = signClaims([
        'limits' => ['users' => 5],
        'exp' => now()->subDays(10)->timestamp,
    ]);

    config(['license.public_key' => $publicKey, 'license.grace_days' => 30]);

    $license = app(License::class);
    $license->store($token);

    expect($license->licensed())->toBeTrue();

    config(['license.grace_days' => 1]);

    $license = app(License::class);
    $license->store($token);

    expect($license->licensed())->toBeFalse();
});

it('without a token the free configuration applies', function () {
    config([
        'license.public_key' => null,
        'license.free.limits' => ['users' => 1],
        'license.free.features' => ['purchasing'],
    ]);

    $license = app(License::class);

    expect($license->licensed())->toBeFalse()
        ->and($license->plan())->toBe('free')
        ->and($license->allows('users', 0))->toBeTrue()
        ->and($license->allows('users', 1))->toBeFalse()
        ->and($license->allowsFeature('purchasing'))->toBeTrue();
});
