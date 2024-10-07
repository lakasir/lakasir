<?php

namespace App\Filament\Tenant\Resources\ProductResource\RelationManagers;

use App\Features\ProductStock;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Laravel\Pennant\Feature;

class PriceUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'priceUnits';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('stock')
                    ->translateLabel()
                    ->helperText(__('Amount that sold or that filled in unit'))
                    ->numeric()
                    ->visible(Feature::active(ProductStock::class))
                    ->disabled(function ($get) {
                        return $get('is_non_stock') || $get('type') == 'service';
                    })
                    ->required(),
                Forms\Components\TextInput::make('unit')
                    ->translateLabel()
                    ->placeholder(__('Meter(m), Box or what you want'))
                    ->required(),
                Forms\Components\TextInput::make('selling_price')
                    ->translateLabel()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix(Setting::get('currency', 'IDR'))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('selling_price')
            ->columns([
                Tables\Columns\TextColumn::make('stock')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('unit')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('selling_price')
                    ->translateLabel()
                    ->money(
                        currency: Setting::get('currency', 'IDR'),
                        locale: Profile::get()->locale
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Price Units');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
