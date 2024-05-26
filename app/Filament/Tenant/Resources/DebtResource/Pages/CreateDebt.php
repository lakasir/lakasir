<?php

namespace App\Filament\Tenant\Resources\DebtResource\Pages;

use App\Filament\Tenant\Resources\DebtResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDebt extends CreateRecord
{
    protected static string $resource = DebtResource::class;
}
