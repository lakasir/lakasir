<?php

namespace App\Observers;

use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
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
        if (!$selling->date) {
            $selling->date = now()->format('Y-m-d');
        }
        $sellings = Selling::all();
        $lastCount = $sellings->count();
        /* TODO: fixing the iteration code <10-08-22, sheenazien8> */
        $selling->code = "SELL" . Str::of($lastCount + 1)->padLeft(4, 0)->value();
        $selling->money_changes = $selling->payed_money - $selling->total_price;
    }

    public function created(Selling $selling)
    {
        foreach ($this->data['products'] as $product) {
            $sellingDetail = new SellingDetail();
            $sellingDetail->fill($product);
            $selling->sellingDetails()->save($sellingDetail);
            $sellingDetail->save();
        }
    }

}
