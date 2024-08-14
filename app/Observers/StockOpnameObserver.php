<?php

namespace App\Observers;

use App\Models\Tenants\StockOpname;

class StockOpnameObserver
{
    public function created(StockOpname $stockOpname): void
    {
    }

    public function creating(StockOpname $stockOpname): void
    {
        $stockOpname->user()->associate(auth()->user());
    }
}
