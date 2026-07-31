<?php

namespace App\Models\Tenants\Traits;

use App\Models\Tenants\Setting;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Number;

trait HasProductPricing
{
    public function initialPriceCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestCalculateIn();
                if ($stock?->first() == null) {
                    return $value;
                }

                return $stock->first()->initial_price;
            },
            set: fn ($value) => $value
        );
    }

    public function sellingPriceCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $stock = $this
                    ->stockLatestCalculateIn();
                if ($stock?->first() == null) {
                    return $value;
                }

                return $stock->first()->selling_price;
            },
            set: fn ($value) => $value
        );
    }

    public function sellingPriceLabelCalculate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return Number::currency($this->selling_price, Setting::get('currency', 'IDR'));
            },
            set: fn ($value) => $value
        );
    }

    public function netProfit(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->selling_price - $this->initial_price
        );
    }
}
