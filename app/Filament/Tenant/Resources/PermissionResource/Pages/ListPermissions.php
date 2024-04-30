<?php

namespace App\Filament\Tenant\Resources\PermissionResource\Pages;

use App\Filament\Tenant\Resources\PermissionResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'mobile' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('guard_name', 'web')),
            'web app' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('guard_name', 'sanctum')),
        ];
    }
}
