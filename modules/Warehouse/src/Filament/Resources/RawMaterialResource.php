<?php

namespace Modules\Warehouse\Filament\Resources;

use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Lakasir\LakasirModule\Abstracts\ModuleResource;
use Modules\Warehouse\Filament\Resources\RawMaterialResource\Pages;
use Modules\Warehouse\Models\RawMaterial;

class RawMaterialResource extends ModuleResource
{
    protected static ?string $model = RawMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->translateLabel()
                ->required(),
            TextInput::make('sku')
                ->unique(
                    ignoreRecord: true
                )
                ->hint(__('Leave it blank to auto generate'))
                ->translateLabel(),
            TextInput::make('quantity')
                ->translateLabel()
                ->numeric()
                ->required(),
            TextInput::make('unit')
                ->translateLabel()
                ->required(),
            TextInput::make('price_per_unit')
                ->translateLabel()
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->prefix(Setting::get('currency', 'IDR'))
                ->required(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->translateLabel(),
                TextColumn::make('sku')
                    ->searchable()
                    ->translateLabel(),
                TextColumn::make('quantity')
                    ->translateLabel(),
                TextColumn::make('unit')
                    ->translateLabel(),
                TextColumn::make('price_per_unit')
                    ->translateLabel()
                    ->money(
                        currency: Setting::get('currency', 'IDR'),
                        locale: Profile::get()->locale
                    ),
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
            'index' => Pages\ListRawMaterials::route('/'),
            'create' => Pages\CreateRawMaterial::route('/create'),
            'edit' => Pages\EditRawMaterial::route('/{record}/edit'),
        ];
    }
}
