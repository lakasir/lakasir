<?php

namespace App\Models\Tenants\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasProductExpiration
{
    protected int $expiredDay = 20;

    public function scopeNearestExpiredProduct(Builder $builder)
    {
        return $builder->whereHas('stocks', function (Builder $builder) {
            $nearestExpired = now()->addDay($this->expiredDay);

            return $builder
                ->whereDate('expired', '<=', $nearestExpired);
        });
    }

    public function expiredStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nearestExpired = now()->addDay($this->expiredDay);

                return $this
                    ->stocks()
                    ->where('stock', '>', 0)
                    ->whereDate('expired', '<=', $nearestExpired)->latest()->first();
            }
        );
    }

    public function hasExpiredStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nearestExpired = now()->addDay($this->expiredDay);

                return $this->stocks()
                    ->where('stock', '>', 0)
                    ->whereDate('expired', '<=', $nearestExpired)->exists();
            }
        );
    }

    public function setExpiredDay(int $day)
    {
        $this->expiredDay = $day;

        return $this;
    }
}
