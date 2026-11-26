<?php

namespace App\Services\License;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Talks to lakasir-cloud. Every call is best-effort: a shop that loses the
 * network keeps trading on the token it already holds, and the 30-day grace
 * window in License::verify() is what makes that safe.
 */
class CloudClient
{
    public function __construct(private License $license) {}

    public function configured(): bool
    {
        return filled(config('license.cloud_url')) && filled($this->licenseKey());
    }

    /**
     * Exchange the stored license key for a fresh token.
     *
     * @return array{ok: bool, message: string}
     */
    public function refresh(): array
    {
        return $this->call('refresh');
    }

    /**
     * Bind this install to a key for the first time.
     *
     * @return array{ok: bool, message: string}
     */
    public function activate(string $key): array
    {
        $result = $this->call('activate', $key);

        if ($result['ok']) {
            \App\Models\License::putKey($key);
        }

        return $result;
    }

    public function licenseKey(): ?string
    {
        return \App\Models\License::key();
    }

    /**
     * @return array{ok: bool, message: string}
     */
    private function call(string $endpoint, ?string $key = null): array
    {
        $key ??= $this->licenseKey();

        if (blank(config('license.cloud_url')) || blank($key)) {
            return ['ok' => false, 'message' => 'No cloud URL or license key is configured.'];
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->post(rtrim((string) config('license.cloud_url'), '/').'/api/license/'.$endpoint, [
                    'key' => $key,
                    'fingerprint' => Fingerprint::make(),
                    'app_version' => (string) (config('app.version') ?? ''),
                ]);
        } catch (ConnectionException $e) {
            Log::warning('License refresh could not reach the cloud', ['error' => $e->getMessage()]);

            return ['ok' => false, 'message' => 'Could not reach the licence server.'];
        }

        if (! $response->successful()) {
            return [
                'ok' => false,
                'message' => $response->json('message') ?: 'The licence server refused the request (HTTP '.$response->status().').',
            ];
        }

        $token = $response->json('data.token');

        if (! is_string($token) || $token === '') {
            return ['ok' => false, 'message' => 'The licence server returned no token.'];
        }

        $previous = \App\Models\License::token();

        $this->license->store($token);
        $this->license->forgetClaims();

        if (! $this->license->licensed()) {
            \App\Models\License::put($previous);
            $this->license->forgetClaims();

            return ['ok' => false, 'message' => 'The returned token did not verify against this install.'];
        }

        \App\Models\License::current()->update([
            'last_refreshed_at' => now(),
            'last_refresh_error' => null,
        ]);

        return ['ok' => true, 'message' => 'Licence refreshed.'];
    }
}
