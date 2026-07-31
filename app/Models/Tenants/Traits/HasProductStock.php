<?php

namespace App\Models\Tenants\Traits;

use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasProductStock
{
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class)
            ->where('is_ready', 1);
    }

    public function scopeStockLatestCalculateIn()
    {
        $usingFifoPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'fifo';
        $usingNormalPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'normal';
        $usingLifoPrice = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'lifo';

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

    public function stockCalculate(): Attribute
    {
        return Attribute::make(
            get: function () {
                $stock = $this
                    ->stockLatestCalculateIn()
                    ->sum('stock');

                return $stock;
            },
            set: fn ($value) => $value
        );
    }
}
