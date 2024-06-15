<?php

namespace App\Services\Tenants;

use App\Models\Tenants\StockOpnameItem;

class StockOpnameItemService
{
    public function update(StockOpnameItem $soi, array $data)
    {
        $soi->fill($data);
        $soi->save();
    }

    public function updateStatus(StockOpnameItem $soi, string $status)
    {

    }
}
