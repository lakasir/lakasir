<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\Pages;

use App\Filament\Tenant\Resources\StockOpnameResource;
use App\Filament\Tenant\Resources\StockOpnameResource\Widgets\StockOpnameOverview;
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
