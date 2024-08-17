<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;

class StockService
{
    private function adjustStockPrepare(Product $product): Stock
    {
        if (Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'normal') {
            /** @var Stock $lastStock */
            $lastStock = $product
                ->stocks()
                ->where('stock', '>', 0)
                ->orderBy('date', 'asc')
                ->latest()
                ->first();
        } else {
            /** @var Stock $lastStock */
            $lastStock = $product->stockLatestCalculateIn()->first();
        }

        return $lastStock;
    }

    public function addStock(Product $product, $qty): void
    {
        $lastStock = $this->adjustStockPrepare($product);

        if ($lastStock) {
            if ($lastStock->stock < $qty) {
                $qty = $qty + $lastStock->stock;
                $lastStock->stock = 0;
                $lastStock->save();
                $this->reduceStock($product, $qty);
            } else {
                $lastStock->stock = $lastStock->stock + $qty;
                $lastStock->save();
            }
        } else {
            $product->stock = $product->stock + $qty;
            $product->save();
        }
    }

    public function reduceStock(Product $product, $qty): void
    {
        $lastStock = $this->adjustStockPrepare($product);

        if ($lastStock) {
            if ($lastStock->stock < $qty) {
                $qty = $qty - $lastStock->stock;
                $lastStock->stock = 0;
                $lastStock->save();
                $this->reduceStock($product, $qty);
            } else {
                $lastStock->stock = $lastStock->stock - $qty;
                $lastStock->save();
            }
        } else {
            $product->stock = $product->stock - $qty;
            $product->save();
        }
    }

    public function create($data, ?Purchasing $purchasing = null): Stock
    {
        $data['stock'] = $data['stock'] ?? 0;
        $data['date'] = $data['date'] ?? now();
        $stock = new Stock();
        $data['init_stock'] = $data['stock'];
        $stock->fill($data);
        $stock->product()->associate(Product::find($data['product_id']));
        if ($purchasing) {
            $stock->purchasing()->associate($purchasing);
        }
        $stock->save();

        return $stock;
    }

    public function update(Stock $stock, array $data, ?Purchasing $purchasing = null)
    {
        $data['init_stock'] = $data['stock'];
        $stock->fill($data);
        $stock->product()->associate(Product::find($data['product_id']));
        if ($purchasing) {
            $stock->purchasing()->associate($purchasing);
        }
        $stock->save();
    }
}
