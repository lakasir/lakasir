<?php

namespace App\Models\Tenants;

use App\Models\Company;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperSetting
 */
class Setting extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = ['key', 'value'];

    private const CACHE_TTL_MINUTES = 3 * 60;

    /**
     * The stored value, or $default when the key has never been set.
     *
     * The cached entry is wrapped in an array so that a stored false, 0 or
     * empty string is a value and not a cache miss, and the default is applied
     * at read time: two callers asking for the same unset key with different
     * defaults each get their own.
     */
    public static function get($key, $default = null)
    {
        $cached = Cache::remember(
            self::cacheKey($key),
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            fn () => ['value' => self::where('key', $key)->value('value')],
        );

        return $cached['value'] ?? $default;
    }

    public static function set($key, $value)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::put(self::cacheKey($key), ['value' => $value], now()->addMinutes(self::CACHE_TTL_MINUTES));
    }

    /**
     * One entry per company: without the suffix the first company to read a
     * key decides the value every other company sees.
     */
    private static function cacheKey(string $key): string
    {
        return 'setting_'.Company::currentId().'_'.$key;
    }
}
