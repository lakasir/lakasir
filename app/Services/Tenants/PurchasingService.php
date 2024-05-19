<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Stock;
use App\Services\Tenants\Traits\HasNumber;

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
        $collection = collect($data['stocks']);
        $total_selling_price = $collection->sum('total_selling_price');
        $total_initial_price = $collection->sum('total_initial_price');
        $data['total_initial_price'] = $total_initial_price;
        $data['total_selling_price'] = $total_selling_price;
        $record = Purchasing::query()->create($data);
        $collection->each(function ($item) use ($data, $record) {
            $item['date'] = $data['date'] ?? now();
            $this->stockService->create($item, $record);
        });

        return $record;
    }

    public function update(mixed $id, $data): Purchasing
    {
        $record = Purchasing::find($id);
        $record->update($data);

        return $record;
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
}
