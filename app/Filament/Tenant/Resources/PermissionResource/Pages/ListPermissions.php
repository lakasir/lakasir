<?php

namespace App\Filament\Tenant\Resources\PermissionResource\Pages;

use App\Filament\Tenant\Resources\PermissionResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getTableQuery(): ?Builder
    {
        return static::getResource()::getEloquentQuery()
            ->limit(10);
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'web' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('guard_name', 'web')),
            'mobile app' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('guard_name', 'sanctum')),
        ];
    }
}
