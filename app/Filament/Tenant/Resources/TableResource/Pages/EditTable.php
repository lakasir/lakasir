<?php

namespace App\Filament\Tenant\Resources\TableResource\Pages;

use App\Filament\Tenant\Resources\TableResource;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTable extends EditRecord
{
    use RedirectToIndex;

    protected static string $resource = TableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
