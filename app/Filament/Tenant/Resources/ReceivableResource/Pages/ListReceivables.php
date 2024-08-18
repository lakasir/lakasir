<?php

namespace App\Filament\Tenant\Resources\ReceivableResource\Pages;

use App\Filament\Tenant\Resources\ReceivableResource;
use App\Filament\Tenant\Resources\ReceivableResource\Widgets\StatsReceivable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceivables extends ListRecords
{
    protected static string $resource = ReceivableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsReceivable::make(),
        ];
    }
}
