<?php

namespace Modules\Warehouse\Filament\Resources\RawMaterialResource\Pages;

use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use Filament\Resources\Pages\CreateRecord;
use Modules\Warehouse\Filament\Resources\RawMaterialResource;

class CreateRawMaterial extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = RawMaterialResource::class;
}
