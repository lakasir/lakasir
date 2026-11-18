<?php

namespace Modules\Warehouse\Filament\Resources\ProductionRecipeResource\Pages;

use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use Filament\Resources\Pages\CreateRecord;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Services\ProductionRecipeService;

class CreateProductionRecipe extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = ProductionRecipeResource::class;

    protected function handleRecordCreation(array $data): Product
    {
        $producttionRecipeService = new ProductionRecipeService;
        $producttionRecipeService->create($data['product_id'], $data['raw_materials']);

        return Product::find($data['product_id']);
    }
}
