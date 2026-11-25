<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LicenseSign extends Command
{
    protected $signature = 'license:sign
                            {--account= : Account identifier}
                            {--plan=pro : Plan name}
                            {--features=* : Entitled features}
                            {--limit=* : Limit as name=value, or name=null}
                            {--days=365 : Days until expiry}';

    protected $description = 'Sign a Lakasir license token with the offline private key';

    public function handle()
    {
        $secret = base64_decode((string) config('license.signing_key'), true);

        if (! $secret || strlen($secret) !== SODIUM_CRYPTO_SIGN_SECRETKEYBYTES) {
            $this->error('Set LICENSE_PRIVATE_KEY to the base64 Ed25519 secret key.');

            return self::FAILURE;
        }

        $limits = [];

        foreach ($this->option('limit') as $pair) {
            [$name, $value] = array_pad(explode('=', $pair, 2), 2, 'null');
            $limits[$name] = $value === 'null' ? null : (int) $value;
        }

        $claims = [
            'v' => 1,
            'account' => $this->option('account'),
            'plan' => $this->option('plan'),
            'limits' => $limits,
            'features' => $this->option('features'),
            'iat' => now()->timestamp,
            'exp' => now()->addDays((int) $this->option('days'))->timestamp,
        ];

        $json = json_encode($claims, JSON_UNESCAPED_SLASHES);
        $signature = sodium_crypto_sign_detached($json, $secret);

        $this->line(base64_encode($json).'.'.base64_encode($signature));

        return self::SUCCESS;
    }
}
