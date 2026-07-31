<?php

namespace App\Models\Tenants\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasProductMedia
{
    public function heroImages(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Str::of($value)->explode(',') : [],
            set: fn ($value) => $value ? Arr::join(is_array($value) ? $value : $value->toArray(), ',') : null
        );
    }

    public function heroImage(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->hero_images ? $this->hero_images[0] : 'https://cdn4.iconfinder.com/data/icons/picture-sharing-sites/32/No_Image-1024.png';
            }
        );
    }
}
