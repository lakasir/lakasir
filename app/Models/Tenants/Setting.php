<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
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
        Cache::put($cacheKey, $value, now()->addMinutes(60));
    }
}
