<?php

namespace App\Filament\Clusters\Inventory;

use App\Filament\Clusters\Inventory;
use App\Filament\Clusters\Inventory\TableResource\Pages;
use App\Filament\Clusters\Traits\HasSubNavigationPosition;
use App\Models\Tenants\Table as TableModel;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TableResource extends Resource
{
    use HasTranslatableResource, HasSubNavigationPosition;

    protected static ?string $model = TableModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $cluster = Inventory::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('number')
                    ->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->translateLabel(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
}
