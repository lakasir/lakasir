<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperSetting
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    private const CACHE_TTL_MINUTES = 3 * 60;

    /**
     * `has()` instead of a truthy `get()`: a stored false, 0 or empty string is
     * a value, not a cache miss. Without this, a setting like
     * `cash_drawer_enabled = false` was re-read from the database on every call
     * and the caller's default would win on a miss.
     */
    public static function get($key, $default = null)
    {
        $cacheKey = 'setting_'.$key;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $setting = self::where('key', $key)->first();
        $result = $setting ? ($setting->value ?? $default) : $default;

        Cache::put($cacheKey, $result, now()->addMinutes(self::CACHE_TTL_MINUTES));

        return $result;
    }

    public static function set($key, $value)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::put('setting_'.$key, $value, now()->addMinutes(self::CACHE_TTL_MINUTES));
    }
}
