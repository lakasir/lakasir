<?php

namespace App\Filament\Tenant\Resources\SellingDetailResource\RelationManagers;

use App\Features\ProductInitialPrice;
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
                Forms\Components\TextInput::make('product_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->translateLabel(),
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
            ->paginated(false);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Items');
    }
}
