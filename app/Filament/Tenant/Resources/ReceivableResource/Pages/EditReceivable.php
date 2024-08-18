<?php

namespace App\Filament\Tenant\Resources\ReceivableResource\Pages;

use App\Filament\Tenant\Resources\ReceivableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceivable extends EditRecord
{
    protected static string $resource = ReceivableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
