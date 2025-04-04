<?php

namespace App\Filament\Clusters\Users\Resources;

use App\Filament\Clusters\Traits\HasSubNavigationPosition;
use App\Filament\Clusters\Users;
use App\Filament\Clusters\Users\Resources\RoleResource\Pages;
use App\Models\Tenants\Role;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    use HasTranslatableResource, HasSubNavigationPosition;

    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $cluster = Users::class;

    // protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->translateLabel()
                    ->required(),
                CheckboxList::make('webPermissions')
                    ->label('Web app permissions')
                    ->bulkToggleable()
                    ->columns(4)
                    ->relationship(
                        titleAttribute: 'name',
                    )
                    ->noSearchResultsMessage('No permissions found.')
                    ->searchable(),
                CheckboxList::make('mobilePermissions')
                    ->label('Mobile app permissions')
                    ->bulkToggleable()
                    ->columns(4)
                    ->relationship(
                        titleAttribute: 'name',
                    )
                    ->noSearchResultsMessage('No permissions found.')
                    ->searchable(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListRoles::route('/'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
            'create' => Pages\CreateRole::route('/create'),
        ];
    }
}
