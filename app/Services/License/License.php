<?php

namespace App\Services\License;

use App\Models\License as LicenseToken;

class License
{
    private ?array $claims = null;

    public function plan(): string
    {
        return $this->claims()['plan'] ?? 'free';
    }

    public function limits(): array
    {
        $claims = $this->claims();

        if ($claims === []) {
            return config('license.free.limits') ?? [];
        }

        return $claims['limits'] ?? [];
    }

    public function limitFor(string $metric): ?int
    {
        $limit = $this->limits()[$metric] ?? null;

        return $limit === null ? null : (int) $limit;
    }

    public function allows(string $metric, int $current): bool
    {
        $limit = $this->limitFor($metric);

        return $limit === null || $current < $limit;
    }

    public function allowsFeature(string $feature): bool
    {
        $claims = $this->claims();

        $features = $claims === []
            ? (config('license.free.features') ?? [])
            : ($claims['features'] ?? []);

        return in_array($feature, $features, true);
    }

    public function licensed(): bool
    {
        return $this->claims() !== [];
    }

    public function store(string $token): void
    {
        LicenseToken::put($token);

        $this->claims = null;
    }

    /**
     * Drop the memoised claims, so the next read verifies the stored token
     * again. Needed after a refresh replaced it in the same request.
     */
    public function forgetClaims(): void
    {
        $this->claims = null;
    }

    public function claims(): array
    {
        return $this->claims ??= $this->verify(LicenseToken::token());
    }

    private function verify(string $token): array
    {
        [$payload, $signature] = array_pad(explode('.', $token, 2), 2, '');

        $publicKey = base64_decode((string) config('license.public_key'), true);
        $json = base64_decode($payload, true);
        $sig = base64_decode($signature, true);

        if (! $publicKey || ! $json || ! $sig || strlen($publicKey) !== SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES) {
            return [];
        }

        if (! sodium_crypto_sign_verify_detached($sig, $json, $publicKey)) {
            return [];
        }

        $claims = json_decode($json, true);

        if (! is_array($claims)) {
            return [];
        }

        $expiry = (int) ($claims['exp'] ?? 0) + config('license.grace_days') * 86400;

        return $expiry < now()->timestamp ? [] : $claims;
    }
}
