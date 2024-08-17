<?php

namespace App\Services\Tenants;

use App\Constants\PurchasingStatus;
use App\Events\RecalculateEvent;
use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Stock;
use App\Services\Tenants\Traits\HasNumber;
use Filament\Notifications\Notification;

class PurchasingService
{
    use HasNumber;

    protected $model = Purchasing::class;

    private StockService $stockService;

    public function __construct()
    {
        $this->stockService = new StockService();
    }

    public function create(array $data): Purchasing
    {
        $data['total_initial_price'] = 0;
        $data['total_selling_price'] = 0;
        $purchasing = Purchasing::create($data);

        return $purchasing;
    }

    public function update(mixed $id, $data): Purchasing
    {
        $purchasing = Purchasing::find($id);
        $purchasing->update($data);

        return $purchasing;
    }

    public function getUpdatedPrice(Purchasing $purchasing): array
    {
        $total_initial_price = 0;
        $total_selling_price = 0;
        $purchasing->stocks->each(
            function (Stock $stock) use (&$total_initial_price, &$total_selling_price) {
                $total_initial_price += $stock->initial_price * $stock->stock;
                $total_selling_price += $stock->selling_price * $stock->stock;
            }
        );

        return [
            'total_initial_price' => $total_initial_price,
            'total_selling_price' => $total_selling_price,
        ];
    }

    public function updateStatus(Purchasing $purchasing, $status): void
    {
        if ($purchasing->stocks->isEmpty()) {
            Notification::make()
                ->title(__('Stocks is empty'))
                ->warning()
                ->send();

            return;
        }
        $purchasing->status = $status;
        if ($status == PurchasingStatus::approved) {
            $purchasing->approved_at = now();
            foreach ($purchasing->stocks as $stock) {
                $stock->is_ready = true;
                $stock->save();
            }
            $products = Product::find($purchasing->stocks()->pluck('product_id'));
            RecalculateEvent::dispatch($products, []);
        }
        $purchasing->save();
        Notification::make('success')
            ->title(__('Status updated'))
            ->success()
            ->send();
    }
}
