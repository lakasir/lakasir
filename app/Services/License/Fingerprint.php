<?php

namespace App\Services\License;

/**
 * A stable identity for this installation. It must survive a migration or a
 * cache clear, and must not change when the shop is renamed, so it is derived
 * from APP_KEY plus the host the app answers on: that pair is what makes this
 * box different from another customer's box.
 */
class Fingerprint
{
    public static function make(): string
    {
        $key = (string) config('app.key');
        $host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost';

        return 'sha256:'.hash('sha256', $key.'|'.$host);
    }

    /**
     * A short, human-readable form for support: the same install can be
     * recognised in a ticket without exposing the whole hash.
     */
    public static function short(): string
    {
        return substr(static::make(), 7, 12);
    }
}
