<?php

namespace App\Filament\Tenant\Resources\DebtResource\Pages;

use App\Filament\Tenant\Resources\DebtResource;
use App\Filament\Tenant\Resources\DebtResource\Widgets\StatsDebt;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDebts extends ListRecords
{
    protected static string $resource = DebtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsDebt::make(),
        ];
    }
}
