<?php

namespace App\Filament\Tenant\Resources\CashDrawerResource\Pages;

use App\Filament\Tenant\Resources\CashDrawerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashDrawers extends ListRecords
{
    protected static string $resource = CashDrawerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('Open'))
                ->createAnother(false),
        ];
    }
}
