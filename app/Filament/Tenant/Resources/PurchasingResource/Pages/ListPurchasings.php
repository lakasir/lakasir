<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\Pages;

use App\Filament\Tenant\Resources\PurchasingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPurchasings extends ListRecords
{
    protected static string $resource = PurchasingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
