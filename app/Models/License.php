<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The install's license token: one row per server, never per company.
 */
class License extends Model
{
    protected $fillable = ['token'];

    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }

    public static function token(): string
    {
        return (string) static::query()->value('token');
    }

    public static function put(string $token): void
    {
        static::current()->update(['token' => $token]);
    }
}
