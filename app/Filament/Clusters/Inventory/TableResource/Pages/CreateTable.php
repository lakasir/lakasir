<?php

namespace App\Filament\Clusters\Inventory\TableResource\Pages;

use App\Filament\Clusters\Inventory\TableResource;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use Filament\Resources\Pages\CreateRecord;

class CreateTable extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = TableResource::class;
}
