<?php

namespace App\Filament\Tenant\Resources\CashDrawerResource\Pages;

use App\Filament\Tenant\Resources\CashDrawerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashDrawer extends EditRecord
{
    protected static string $resource = CashDrawerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
