<?php

namespace Modules\Warehouse\Filament\Resources\RawMaterialResource\Pages;

use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Warehouse\Filament\Resources\RawMaterialResource;

class EditRawMaterial extends EditRecord
{
    use RedirectToIndex;

    protected static string $resource = RawMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
