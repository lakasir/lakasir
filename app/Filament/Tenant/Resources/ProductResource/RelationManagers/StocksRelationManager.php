<?php

namespace App\Filament\Tenant\Resources\ProductResource\RelationManagers;

use App\Models\Tenants\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StocksRelationManager extends RelationManager
{
    protected static string $relationship = 'stocks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('stock')
            ->columns([
                Tables\Columns\TextColumn::make('stock'),
                Tables\Columns\TextColumn::make('init_stock'),
                Tables\Columns\TextColumn::make('date')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('expired')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('initial_price')
                    ->translateLabel()
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('selling_price')
                    ->translateLabel()
                    ->money(Setting::get('currency', 'IDR')),
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
        return __('Stock History');
    }
}
