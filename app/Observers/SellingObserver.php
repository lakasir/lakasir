<?php

namespace App\Observers;

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Str;

class SellingObserver extends AbstractObserver implements DataAwareRule
{
    protected $data = [];

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function creating(Selling $selling)
    {
        if (! $selling->date) {
            $selling->date = now()->format('Y-m-d H:i:s');
        }
        $sellings = Selling::all();
        $lastCount = $sellings->count();
        /* TODO: fixing the iteration code <10-08-22, sheenazien8> */
        $selling->code = 'SELL'.Str::of($lastCount + 1)->padLeft(4, 0)->value();
        // $selling->money_changes = $selling->payed_money - $selling->total_price;
        if (Setting::get('cash_drawer_enabled', false)) {
            $selling->cash_drawer_id = CashDrawer::lastOpened()->first()->id;
        }
        $selling->user()->associate(auth()->user());
    }
}
