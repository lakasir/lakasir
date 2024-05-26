<?php

namespace App\Filament\Tenant\Resources\MemberResource\Pages;

use App\Filament\Tenant\Resources\MemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function getRedirectUrl(): string
    {
        return '/member/members';
    }
}
