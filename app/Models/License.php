<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The install's license state: one row per server, never per company.
 */
class License extends Model
{
    protected $fillable = ['key', 'token', 'last_refreshed_at', 'last_refresh_error'];

    protected $casts = [
        'last_refreshed_at' => 'datetime',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }

    public static function token(): string
    {
        return (string) static::query()->value('token');
    }

    public static function key(): ?string
    {
        return static::query()->value('key');
    }

    public static function put(string $token): void
    {
        static::current()->update(['token' => $token]);
    }

    public static function putKey(string $key): void
    {
        static::current()->update(['key' => $key]);
    }
}
