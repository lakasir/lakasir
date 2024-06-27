<?php

namespace App\Filament\Tenant\Resources\ProductResource\Pages;

use App\Filament\Tenant\Resources\ProductResource\RelationManagers\SellingDetailsRelationManager;
use App\Filament\Tenant\Resources\ProductResource\RelationManagers\StocksRelationManager;
use App\Filament\Tenant\Resources\ProductResource\Widgets\StatsProduct;
use App\Filament\Tenant\Resources\ProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationGroup;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsProduct::make(['recordId' => $this->record->id]),
        ];
    }

    public function getRelationManagers(): array
    {
        $relations = [
            SellingDetailsRelationManager::make(),
        ];
        if (! $this->record->is_non_stock) {
            return [
                RelationGroup::make('', array_merge(
                    [
                        StocksRelationManager::make(),
                    ],
                    $relations,
                )),
            ];
        }

        return $relations;
    }
}
