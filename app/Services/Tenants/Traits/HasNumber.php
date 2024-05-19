<?php

namespace App\Services\Tenants\Traits;

trait HasNumber
{
    public function generateNumber($prefix, $length = 8): string
    {
        $date = now()->format('Ymd');

        $countForToday = ($this->model::whereDate('created_at', today())->latest()->first()?->id ?? 0);
        $sequentialNumber = str_pad($countForToday + 1, $length, '0', STR_PAD_LEFT);

        return $prefix.'-'.$date.$sequentialNumber;
    }
}
