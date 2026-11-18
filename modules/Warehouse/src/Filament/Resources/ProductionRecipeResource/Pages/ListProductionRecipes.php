<?php

namespace Modules\Warehouse\Filament\Resources\ProductionRecipeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource;

class ListProductionRecipes extends ListRecords
{
    protected static string $resource = ProductionRecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('Add recipe')),
        ];
    }
}
