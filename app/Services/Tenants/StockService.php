<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    /**
     * The batch the sale (or a restock) works on: the newest one for the
     * "normal" selling method, otherwise the first batch the method sells from.
     */
    private function adjustStockPrepare(Product $product): ?Stock
    {
        if (Setting::get('selling_method', config('setting.selling_method')) == 'normal') {
            /** @var Stock $lastStock */
            $lastStock = $product
                ->stocks()
                ->where('type', 'in')
                ->where('stock', '>', 0)
                ->newestFirst()
                ->first();
        } else {
            /** @var Stock $lastStock */
            $lastStock = $product->stockLatestCalculateIn()->first();
        }

        return $lastStock;
    }

    public function addStock(Product $product, float $qty): void
    {
        $lastStock = $this->adjustStockPrepare($product);

        if ($lastStock) {
            $lastStock->stock = $lastStock->stock + $qty;
            $lastStock->save();
        } else {
            $product->stock = $product->stock + $qty;
            $product->save();
        }
    }

    /**
     * Take $qty out of the product's batches.
     *
     * Inside a transaction the product row and its batches are locked and the
     * whole quantity is checked before anything is written, so two cashiers
     * selling the last unit cannot both succeed; a sale that no longer fits
     * throws a validation error and its transaction rolls back.
     *
     * @throws ValidationException
     */
    public function reduceStock(Product $product, float $qty): void
    {
        $qty = (float) $qty;

        if (DB::transactionLevel() === 0) {
            // Nothing to lock against: behave as before, one batch at a time.
            $this->reduceUnlocked($product, $qty);

            return;
        }

        /** @var Product $locked */
        $locked = Product::whereKey($product->id)->lockForUpdate()->firstOrFail();
        $method = Setting::get('selling_method', config('setting.selling_method'));
        $batches = $locked->stocks()
            ->where('type', 'in')
            ->where('stock', '>', 0)
            ->when(
                $method === 'normal',
                fn ($query) => $query->newestFirst(),
                fn ($query) => $method === 'lifo'
                    ? $query->orderByDesc('created_at')->orderByDesc('id')
                    : $query->orderBy('created_at')->orderBy('id'),
            )
            ->lockForUpdate()
            ->get();

        if ($batches->isEmpty()) {
            // A shop that sells without batches keeps its stock on the product.
            if ((float) $locked->stock < $qty) {
                throw ValidationException::withMessages([
                    'products' => __('The stock of product less than from you request.'),
                ]);
            }
            $locked->stock = (float) $locked->stock - $qty;
            $locked->save();

            return;
        }

        if ($batches->sum(fn (Stock $batch) => (float) $batch->stock) < $qty) {
            throw ValidationException::withMessages([
                'products' => __('The stock of product less than from you request.'),
            ]);
        }

        $remaining = $qty;
        foreach ($batches as $batch) {
            if ($remaining <= 0) {
                break;
            }
            $take = min($remaining, (float) $batch->stock);
            $batch->stock = (float) $batch->stock - $take;
            $batch->save();
            $remaining -= $take;
        }
    }

    /**
     * The unlocked path: consume batch by batch, then the product's own stock.
     */
    private function reduceUnlocked(Product $product, float $qty): void
    {
        $remaining = $qty;

        while ($remaining > 0) {
            $lastStock = $this->adjustStockPrepare($product);
            if (! $lastStock) {
                $product->stock = $product->stock - $remaining;
                $product->save();

                return;
            }
            if ($lastStock->stock < $remaining) {
                $remaining -= $lastStock->stock;
                $lastStock->stock = 0;
            } else {
                $lastStock->stock = $lastStock->stock - $remaining;
                $remaining = 0;
            }
            $lastStock->save();
        }
    }

    public function create(array $data, ?Purchasing $purchasing = null): Stock
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
