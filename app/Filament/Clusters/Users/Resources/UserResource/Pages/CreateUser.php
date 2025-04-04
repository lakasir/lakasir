<?php

namespace App\Filament\Clusters\Users\Resources\UserResource\Pages;

use App\Filament\Clusters\Users\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return '/member/users';
    }
}
