<?php

namespace App\Helpers;

use App\Models\Setting as ModelsSetting;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Setting
 * @author sheenazien8
 */
class Setting
{
    /**
     * Setter for
     *
     * @param string $key
     * @param mix $key
     * @return ModelsSetting
     */
    public function set(string $key, $value): ModelsSetting
    {
        return ModelsSetting::create([
            'key' => $key,
            'value' => $value
        ]);
    }

    /**
     * Getter for
     *
     * @param string $key
     * @return Collection
     */
    public function get(string $key): Collection
    {
        return ModelsSetting::when($key, function ($query) use ($key)
        {
            $query->where('key', $key);
        })->get();
    }
}
