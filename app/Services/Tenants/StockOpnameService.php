<?php

namespace App\Services\Tenants;

use App\Constants\StockOpnameStatus;
use App\Events\RecalculateEvent;
use App\Models\Tenants\Product;
use App\Models\Tenants\StockOpname;
use App\Models\Tenants\StockOpnameItem;
use App\Services\Tenants\Traits\HasNumber;

class StockOpnameService
{
    use HasNumber;

    protected string $model = StockOpname::class;

    private StockService $stockService;

    public function __construct()
    {
        $this->stockService = new StockService;
    }

    public function create(array $data): StockOpname
    {
        $stockOpname = new StockOpname();
        $stockOpname->fill($data);
        $stockOpname->save();
        $collection = collect($data['stock_opname_items']);
        $collection->map(function ($item) use ($stockOpname) {
            $product = Product::find($item['product_id']);
            $sItem = new StockOpnameItem();
            $sItem->fill($item);
            $sItem->product()->associate($product);
            $sItem->stockOpname()->associate($stockOpname);
            $sItem->save();
        });

        return $stockOpname;
    }

    public function update(StockOpname $stockOpname, $data): StockOpname
    {
        $stockOpname->fill($data);
        $stockOpname->save();

        return $stockOpname;
    }

    public function delete(StockOpname $stockOpname): void
    {
        $stockOpname->stockOpnameItems->each(function (StockOpnameItem $sOItem) {
            $sOItem->product->stock = $sOItem->product->stock + $sOItem->amount;
            $sOItem->product->save();
        });
        $stockOpname->delete();
    }

    public function updateStatus(StockOpname $so, $status)
    {
        $so->fill([
            'status' => $status,
        ]);
        $so->save();
        if ($status == StockOpnameStatus::approved) {
            foreach ($so->stockOpnameItems as $soItem) {
                if ($soItem->adjustment_type == 'manual_input') {
                    $this->stockService->addStock($soItem->product, $soItem->amount);
                } else {
                    $this->stockService->reduceStock($soItem->product, $soItem->amount);
                }
            }
            RecalculateEvent::dispatch(Product::whereIn('id', $so->stockOpnameItems->pluck('product_id'))->get(), []);
        }
    }
}
