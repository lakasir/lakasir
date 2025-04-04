<?php

namespace App\Filament\Clusters\Users\Resources;

use App\Filament\Clusters\Traits\HasSubNavigationPosition;
use App\Filament\Clusters\Users;
use App\Filament\Clusters\Users\Resources\PermissionResource\Pages;
use App\Traits\HasTranslatableResource;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    use HasSubNavigationPosition, HasTranslatableResource;

    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $cluster = Users::class;

    // protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->translateLabel(),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
        ];
    }
}
