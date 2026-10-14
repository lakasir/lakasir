<?php

namespace App\Filament\Tenant\Resources\ProductResource\RelationManagers;

use App\Features\ProductInitialPrice;
use App\Filament\Tenant\Resources\SellingResource;
use App\Models\Tenants\SellingDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SellingDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'sellingDetails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->recordUrl(function (SellingDetail $record) {
                return SellingResource::getUrl('view', ['record' => $record->selling_id]);
            })
            ->columns([
                Tables\Columns\TextColumn::make('selling.code')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('selling.date')
                    ->label(__('Date')),
                Tables\Columns\TextColumn::make('qty')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('price')
                    ->translateLabel()
                    ->sortable()
                    ->money(currency_code()),
                Tables\Columns\TextColumn::make('discount')
                    ->getStateUsing(fn (SellingDetail $sellingDetail) => price_format((float) $sellingDetail->discount_price))
                    ->translateLabel()
                    ->sortable()
                    ->money(currency_code()),
                Tables\Columns\TextColumn::make('discount_price')
                    ->getStateUsing(fn (SellingDetail $sellingDetail) => price_format(
                        (float) ($sellingDetail->price - $sellingDetail->discount_price)
                    ))
                    ->translateLabel()
                    ->sortable()
                    ->money(currency_code()),
                Tables\Columns\TextColumn::make('cost')
                    ->translateLabel()
                    ->visible(feature(ProductInitialPrice::class))
                    ->sortable()
                    ->money(currency_code()),
            ])
            ->filters([
                //
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
        return __('Selling Details');
    }
}
