<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        $usingFifoPrice = Setting::get('selling_method', 'fifo') == 'fifo';
        $usingNormalPrice = Setting::get('selling_method', 'fifo') == 'normal';
        $usingLifoPrice = Setting::get('selling_method', 'fifo') == 'lifo';

        return $this
            ->stocks()
            ->where('type', 'in')
            ->when($usingNormalPrice, fn (Builder $query) => $query->orderBy('date')->latest())
            ->when($usingFifoPrice, fn (Builder $query) => $query
                ->where('stock', '>', 0)
                ->orderBy('created_at')->orderBy('date'))
            ->when($usingLifoPrice, fn (Builder $query) => $query
                ->where('stock', '>', 0)
                ->orderByDesc('created_at')->orderByDesc('date'));
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
