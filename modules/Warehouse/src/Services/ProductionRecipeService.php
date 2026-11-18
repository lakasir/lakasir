<?php

namespace Modules\Warehouse\Services;

use Modules\Warehouse\Models\ProductionRecipeItem;

class ProductionRecipeService
{
    /**
     * Replace a product's recipe with the given rows.
     *
     * @param  array<int, array{raw_material_id: int|string, quantity_required: int|float|string, quantity_unit?: string|null}>  $rows
     */
    public function create(int $productId, array $rows): void
    {
        ProductionRecipeItem::query()->where('product_id', $productId)->delete();

        foreach ($rows as $row) {
            if (empty($row['raw_material_id'])) {
                continue;
            }

            ProductionRecipeItem::query()->create([
                'product_id' => $productId,
                'raw_material_id' => $row['raw_material_id'],
                'quantity_required' => (float) ($row['quantity_required'] ?? 0),
                'quantity_unit' => $row['quantity_unit'] ?? null,
            ]);
        }
    }
}
