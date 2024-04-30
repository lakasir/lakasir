<?php

namespace App\Traits;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait UseTimezoneAwareQuery
{
    public function scopeTimezoneBetween(Builder $builder, string $column, array $dates): Builder
    {
        /** @var Carbon $start */
        [$start, $end] = $dates;
        dump($start, $end);
        $timezone = Filament::auth()->user()->profile?->timezone;
        $startDate = Carbon::parse($start)->setTimezone($timezone);
        $endDate = Carbon::parse($end)->setTimezone($timezone);
        dd($startDate, $endDate);

        return $builder->whereBetween($column, [$startDate, $endDate]);
    }
}
