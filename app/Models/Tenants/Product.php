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

    public function stock(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this->stocks()
                    ->in()
                    ->sum('stock');
                if ($stock == 0) {
                    return $value;
                }
                return $stock + $value;
            },
            set: fn ($value) => $value
        );
    }

    public function initialPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this->stocks()
                    ->latestIn();
                if ($stock == null) {
                    return $value;
                }
                return $stock->initial_price;
            },
            set: fn ($value) => $value
        );
    }

    public function sellingPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this->stocks()
                    ->latestIn();
                if ($stock == null) {
                    return $value;
                }
                return $stock->selling_price;
            },
            set: fn ($value) => $value
        );
    }

    public function heroImages(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Str::of($value)->explode(',') : [],
            set: fn ($value) => $value ? Arr::join(is_array($value) ? $value : $value->toArray(), ',') : null
        );
    }
}
