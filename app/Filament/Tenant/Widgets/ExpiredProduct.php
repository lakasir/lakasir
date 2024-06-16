<?php

namespace App\Filament\Tenant\Widgets;

use App\Models\Tenants\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ExpiredProduct extends BaseWidget
{
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::nearestExpiredProduct()
            )
            ->columns([
                TextColumn::make('name')
                    ->translateLabel(),
                TextColumn::make('category.name')
                    ->translateLabel(),
                TextColumn::make('expired')
                    ->getStateUsing(fn (Product $product) => $product->expired_stock->expired)
                    ->date()
                    ->translateLabel(),
            ]);
    }
}
