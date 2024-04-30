<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\SellingResource\Pages;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SellingResource extends Resource
{
    protected static ?string $model = Selling::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Selling History';

    protected static ?string $breadcrumb = 'Selling History';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Cashier')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('user', function (Builder $query) use ($search) {
                            return $query
                                ->where('email', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('member.name'),
                TextColumn::make('customer_number'),
                TextColumn::make('date'),
                TextColumn::make('total_price')
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('tax_price')
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('total_cost')
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
            ])
            ->searchPlaceholder('Search (Code, User, Customer Number')
            ->filters([
                SelectFilter::make('Cashier')
                    ->relationship('user', 'name'),
            ])
            ->actions([
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
            'index' => Pages\ListSellings::route('/'),
            'view' => Pages\ViewSelling::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            SellingResource\Widgets\SellingRevenueOverview::class,
        ];
    }
}
