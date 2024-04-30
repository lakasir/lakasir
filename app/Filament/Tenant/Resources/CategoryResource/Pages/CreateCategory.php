<?php

namespace App\Filament\Tenant\Resources\CategoryResource\Pages;

use App\Filament\Tenant\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return '/member/categories';
    }
}
