<?php

namespace App\Filament\Clusters\Inventory\StockOpnameResource\Pages;

use App\Filament\Clusters\Inventory\StockOpnameResource;
use App\Filament\Clusters\Inventory\StockOpnameResource\Widgets\StockOpnameOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockOpnames extends ListRecords
{
    protected static string $resource = StockOpnameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StockOpnameOverview::make(),
        ];
    }
}
