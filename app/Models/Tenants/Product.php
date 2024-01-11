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

    public function scopeStockLatestIn()
    {
        return $this
                ->stocks()
                ->where('type', 'in')
                ->where('stock', '>', 0)
                ->orderBy('date');
    }

    public function stock(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this->stockLatestIn()
                    ->sum('stock');
                return $stock + $value;
            },
            set: fn ($value) => $value
        );
    }

    public function initialPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this->stockLatestIn();
                if ($stock?->first() == null) {
                    return $value;
                }
                return $stock->first()->initial_price;
            },
            set: fn ($value) => $value
        );
    }

    public function sellingPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this->stockLatestIn();
                if ($stock?->first() == null) {
                    return $value;
                }
                return $stock->first()->selling_price;
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
