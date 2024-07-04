<?php

namespace App\Filament\Tenant\Widgets;

use App\Models\Tenants\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ExpiredProduct extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::nearestExpiredProduct()
            )
            ->columns([
                TextColumn::make('name')
                    ->translateLabel(),
                TextColumn::make('expired')
                    ->getStateUsing(fn (Product $product) => $product->expired_stock?->expired)
                    ->date()
                    ->translateLabel(),
            ])
            ->recordUrl(function (Product $product) {
                return '/member/products/'.$product->getKey().'/edit';
            });
    }
}
