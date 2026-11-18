<?php

namespace Modules\Warehouse\Filament\Resources\ProductionRecipeResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Modules\Warehouse\Filament\Actions\ProductionAction;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers\ProductionBathcesRelationManager;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers\ProductionRecipeItemsRelationManager;

class ViewProductionRecipe extends ViewRecord
{
    protected static string $resource = ProductionRecipeResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('Warehouse::app.production_recipe.show', [
            'product' => $this->record->name,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ProductionAction::make(Action::class),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            ProductionRecipeItemsRelationManager::class,
            ProductionBathcesRelationManager::class,
        ];
    }
}
