<?php

namespace App\Filament\Clusters\Inventory\PurchasingResource\Pages;

use App\Filament\Clusters\Inventory\PurchasingResource;
use App\Filament\Clusters\Inventory\PurchasingResource\Widgets\PurchaseOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPurchasings extends ListRecords
{
    protected static string $resource = PurchasingResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PurchaseOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
