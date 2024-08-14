<?php

namespace App\Services\Tenants;

use App\Constants\StockOpnameStatus;
use App\Events\RecalculateEvent;
use App\Models\Tenants\Product;
use App\Models\Tenants\StockOpname;
use App\Models\Tenants\StockOpnameItem;
use App\Services\Tenants\Traits\HasNumber;
use Filament\Notifications\Notification;

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
            $sOItem->product->stock = $sOItem->product->stock + $sOItem->actual_stock;
            $sOItem->product->save();
        });
        $stockOpname->delete();
    }

    public function updateStatus(StockOpname $so, $status)
    {
        $so->status = $status;
        if ($status == StockOpnameStatus::approved) {
            $so->approved_at = now();
            if ($so->stockOpnameItems->isEmpty()) {
                Notification::make()
                    ->title(__('Stock Opname Item is required'))
                    ->warning()
                    ->send();

                return;
            }
            foreach ($so->stockOpnameItems as $soItem) {
                if ($soItem->missing_stock < 0) {
                    $this->stockService->addStock($soItem->product, $soItem->missing_stock * -1);
                } else {
                    $this->stockService->reduceStock($soItem->product, $soItem->missing_stock);
                }
            }
            RecalculateEvent::dispatch(Product::whereIn('id', $so->stockOpnameItems->pluck('product_id'))->get(), []);
        }
        $so->save();
    }
}
