<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * 
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereValue($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $cacheKey = 'setting_'.$key;
        $result = null;
        if (! Cache::get('setting_'.$key)) {
            $setting = self::where('key', $key)->first();

            $result = $setting ? $setting->value : $default;

            Cache::put($cacheKey, $result, now()->addMinutes(3 * 60));

            return $result;
        }

        return Cache::get($cacheKey);
    }

    public static function set($key, $value)
    {
        // Update the value in the database
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Update the value in cache
        $cacheKey = 'setting_'.$key;
        Cache::put($cacheKey, $value, now()->addMinutes(3 * 60));
    }
}
