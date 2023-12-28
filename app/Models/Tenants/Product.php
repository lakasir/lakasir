<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'hero_images_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function heroImages(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Str::of($value)->explode(',') : null,
            set: fn ($value) => $value ? Arr::join($value, ',') : null
        );
    }
}
