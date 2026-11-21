<?php

namespace App\Observers;

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;

class SellingObserver extends AbstractObserver
{
    public function creating(Selling $selling)
    {
        if (! $selling->date) {
            $selling->date = now()->format('Y-m-d H:i:s');
        }
        if (Setting::get('cash_drawer_enabled', false)) {
            $selling->cash_drawer_id = CashDrawer::lastOpened()->first()->id;
        }
        $selling->user()->associate(auth()->user());
    }

    /**
     * The sale code follows the row id: no full-table count, and no two sales
     * can share a code (the column is unique). The id is only known after the
     * insert, so the code is filled in and saved quietly here.
     */
    public function created(Selling $selling)
    {
        if ($selling->code) {
            return;
        }
        $selling->code = 'SELL'.str_pad((string) $selling->id, 4, '0', STR_PAD_LEFT);
        $selling->saveQuietly();
    }
}
