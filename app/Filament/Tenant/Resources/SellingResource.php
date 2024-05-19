<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\SellingDetailResource\RelationManagers\SellingDetailsRelationManager;
use App\Filament\Tenant\Resources\SellingResource\Pages;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
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
            ->defaultSort('created_at', 'desc')
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
                TextColumn::make('member.name')
                    ->default('-'),
                TextColumn::make('customer_number')
                    ->default('-'),
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
                SelectFilter::make('user_id')
                    ->label('Cashier')
                    ->options(
                        User::all()->mapWithKeys(fn (User $user) => [$user->id => $user->cashier_name]
                        )
                    ),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Card::make()
                    ->schema([
                        TextEntry::make('total_price')
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('total_cost')
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('tax_price')
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('tax')
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('payed_money')
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('money_changes')
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('user.name')
                            ->label('Cashier'),
                        TextEntry::make('member.name')
                            ->default('-'),
                        TextEntry::make('customer_number')
                            ->default('-'),
                        TextEntry::make('cashDrawer.cash')
                            ->default('-'),
                        TextEntry::make('paymentMethod.name'),
                        TextEntry::make('note'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SellingDetailsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellings::route('/'),
            'view' => Pages\ViewSelling::route('/{record}'),
        ];
    }
}
