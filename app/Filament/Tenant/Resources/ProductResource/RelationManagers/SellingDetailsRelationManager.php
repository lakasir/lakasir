<?php

namespace App\Filament\Tenant\Resources\ProductResource\RelationManagers;

use App\Features\ProductInitialPrice;
use App\Filament\Tenant\Resources\SellingResource;
use App\Models\Tenants\SellingDetail;
use App\Models\Tenants\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

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
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('discount')
                    ->getStateUsing(fn (SellingDetail $sellingDetail) => Number::currency(
                        $sellingDetail->discount_price, Setting::get('curerncy', 'IDR'))
                    )
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('discount_price')
                    ->getStateUsing(fn (SellingDetail $sellingDetail) => Number::currency(
                        $sellingDetail->price - $sellingDetail->discount_price, Setting::get('curerncy', 'IDR'))
                    )
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('cost')
                    ->translateLabel()
                    ->visible(feature(ProductInitialPrice::class))
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
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
