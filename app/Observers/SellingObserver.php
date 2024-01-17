<?php

namespace App\Observers;

use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
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
        if (!$selling->date) {
            $selling->date = now()->format('Y-m-d');
        }
        $sellings = Selling::all();
        $lastCount = $sellings->count();
        /* TODO: fixing the iteration code <10-08-22, sheenazien8> */
        $selling->code = "SELL" . Str::of($lastCount + 1)->padLeft(4, 0)->value();
        $selling->money_changes = $selling->payed_money - $selling->total_price;
        if (Setting::get('cash_drawer_enabled', false)) {
            $selling->cash_drawer_id = CashDrawer::lastOpened()->first()->id;
        }
    }

    public function created(Selling $selling)
    {
        foreach ($this->data['products'] as $productRequest) {
            $product = Product::find($productRequest['product_id']);
            if (!$product->is_non_stock) {
                $this->fifo($product, $productRequest['qty']);
            }
            if (!$this->data['friend_price']) {
                $productRequest['price'] = $product->selling_price * $productRequest['qty'];
            }
            $sellingDetail = new SellingDetail();
            $sellingDetail->fill($productRequest);
            $selling->sellingDetails()->save($sellingDetail);
            $sellingDetail->save();
        }
    }

    private function fifo(Product $product, $qty)
    {
        $lastStock = $product->stockLatestIn()->first();
        if ($lastStock) {
            if ($lastStock->stock < $qty) {
                $qty = $qty - $lastStock->stock;
                $lastStock->stock = 0;
                $lastStock->save();
                $this->fifo($product, $qty);
            } else {
                $lastStock->stock = $lastStock->stock - $qty;
                $lastStock->save();
            }
        } else {
            $product->stock = $product->stock - $qty;
            $product->save();
        }
    }
}
