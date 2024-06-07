<?php

namespace App\Filament\Tenant\Resources\SellingDetailResource\RelationManagers;

use App\Models\Tenants\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SellingDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'sellingDetails';

    protected static ?string $title = 'Items';

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
                    ->label(__('Item Name')),
                Tables\Columns\TextColumn::make('qty')
                    ->label(__('Qty')),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('discount_price')
                    ->label(__('Discount price'))
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                Tables\Columns\TextColumn::make('cost')
                    ->label(__('Cost'))
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
            ])
            ->paginated(false);
    }
}
