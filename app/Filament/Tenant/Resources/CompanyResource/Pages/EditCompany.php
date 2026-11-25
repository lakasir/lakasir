<?php

namespace App\Filament\Tenant\Resources\CompanyResource\Pages;

use App\Filament\Tenant\Resources\CompanyResource;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getRedirectUrl(): string
    {
        return CompanyResource::getUrl('index');
    }
}
