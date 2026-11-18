<?php

namespace Modules\Warehouse\Extends\Product;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Split;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
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

        // Filament repeaters key their items by UUID; a plain list leaves every
        // row's children unbound, so the selects render empty.
        $mapProductionRecipeItems = $productionRecipeItems->mapWithKeys(function (ProductionRecipeItem $recipeItem) {
            return [(string) Str::uuid() => [
                'raw_material_id' => $recipeItem->raw_material_id,
                'quantity_required' => $recipeItem->quantity_required,
                'quantity_unit' => $recipeItem->quantity_unit ?: $recipeItem->rawMaterial?->base_unit,
                'price_per_need' => $recipeItem->rawMaterial?->costFor(
                    (float) $recipeItem->quantity_required,
                    $recipeItem->quantity_unit ?? $recipeItem->rawMaterial->base_unit,
                ) ?? 0,
            ]];
        });

        // ExtendModuleForm gives the tab `->statePath(str(packageName)->snake('-'))`,
        // so the filled state must use that same key, not the raw package name.
        $statePath = str(getPackageModuleName('Warehouse'))->snake('-')->lower()->value();

        $data = array_merge($data, [
            $statePath => [
                'recipes' => $mapProductionRecipeItems->all(),
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
