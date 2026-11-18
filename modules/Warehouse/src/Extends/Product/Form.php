<?php

namespace Modules\Warehouse\Extends\Product;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Split;
use Illuminate\Database\Eloquent\Collection;
use Lakasir\LakasirModule\Forms\ModuleForm;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers\ProductionRecipeItemsRelationManager;
use Modules\Warehouse\Models\ProductionRecipeItem;

class Form extends ModuleForm
{
    public static function getTitle(): string
    {
        return __('Warehouse::app.form.title');
    }

    public static function fill(array $data): array
    {
        /** @var Collection<ProductionRecipeItem> $productionRecipeItems */
        $productionRecipeItems = ProductionRecipeItem::whereProductId(self::$record->id)->get();

        $mapProductionRecipeItems = $productionRecipeItems->map(function (ProductionRecipeItem $recipeItem) {
            return [
                'raw_material_id' => $recipeItem->raw_material_id,
                'quantity_required' => $recipeItem->quantity_required,
                'price_per_need' => $recipeItem->rawMaterial->price_per_unit * $recipeItem->quantity_required,
            ];
        });

        $data = array_merge($data, [
            getPackageModuleName('Warehouse') => [
                'recipes' => $mapProductionRecipeItems,
            ],
        ]);

        return $data;
    }

    public static function make(): array
    {
        return [
            Repeater::make('recipes')
                ->label(__('Warehouse::app.form.recipes'))
                ->default([
                    ['raw_material_id' => 1, 'quantity_required' => 0.2],
                ])
                ->schema([
                    Split::make(ProductionRecipeItemsRelationManager::getDefaultForm()),
                ]),
        ];
    }
}
