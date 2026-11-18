<?php

namespace Modules\Warehouse\Services;

use Modules\Warehouse\Models\ProductionRecipe;

class ProductionRecipeService
{
    public function create(int $productId, array $data)
    {
        foreach ($data as $material) {
            ProductionRecipe::create([
                'product_id' => $productId,
                'raw_material_id' => $material['raw_material_id'],
                'quantity_required' => $material['quantity_required'],
            ]);
        }
    }
}
