<?php

namespace App\Filament\Tenant\Resources;

use App\Features\ProductInitialPrice;
use App\Filament\Tenant\Resources\SellingDetailResource\RelationManagers\SellingDetailsRelationManager;
use App\Filament\Tenant\Resources\SellingResource\Pages;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class SellingResource extends Resource
{
    use HasTranslatableResource;

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
                    ->label(__('Cashier'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('user', function (Builder $query) use ($search) {
                            return $query
                                ->where('email', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('member.name')
                    ->translateLabel()
                    ->default('-'),
                TextColumn::make('customer_number')
                    ->translateLabel()
                    ->default('-'),
                TextColumn::make('date')
                    ->translateLabel(),
                TextColumn::make('total_price')
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('tax_price')
                    ->translateLabel()
                    ->sortable()
                    ->visible(feature(ProductInitialPrice::class))
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('total_cost')
                    ->translateLabel()
                    ->sortable()
                    ->visible(feature(ProductInitialPrice::class))
                    ->money(Setting::get('currency', 'IDR')),
            ])
            ->searchPlaceholder('Search (Code, User, Customer Number')
            ->filters([
                SelectFilter::make('user_id')
                    ->label(__('Cashier'))
                    ->options(User::all()->mapWithKeys(fn (User $user) => [$user->id => $user->cashier_name])),
                Filter::make('date')
                    ->form([
                        DatePicker::make('start_date')
                            ->native(false)
                            ->format('Y-m-d')
                            ->date()
                            ->closeOnDateSelection(),
                        DatePicker::make('end_date')
                            ->native(false)
                            ->format('Y-m-d')
                            ->date()
                            ->closeOnDateSelection(),
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if (! ($data['start_date'] && $data['end_date'])) {
                            return null;
                        }

                        return Carbon::parse($data['start_date'])->toFormattedDateString().' s/d '.Carbon::parse($data['end_date'])->toFormattedDateString();
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $startDate = $data['start_date'];
                        $endDate = $data['end_date'];

                        return $query
                            ->when($startDate && $endDate, fn (Builder $builder) => $builder->whereBetween('date', [$startDate, $endDate]));
                    }),
            ])
            ->deferFilters();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Card::make()
                    ->schema([
                        TextEntry::make('voucher')
                            ->label(__('Voucher')),
                        TextEntry::make('discount_price')
                            ->label(__('Discount price'))
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('total_price')
                            ->label(__('Total price'))
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('total_cost')
                            ->label(__('Total cost'))
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('tax_price')
                            ->label(__('Tax price'))
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('tax')
                            ->label(__('Tax'))
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('payed_money')
                            ->label(__('Payed money'))
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('money_changes')
                            ->label(__('Money changes'))
                            ->money(Setting::get('currency', 'IDR')),
                        TextEntry::make('user')
                            ->getStateUsing(function (Selling $selling) {
                                return $selling->user->name ?? $selling->user->email;
                            })
                            ->label(__('Cashier')),
                        TextEntry::make('member.name')
                            ->label(__('Member'))
                            ->default('-'),
                        TextEntry::make('customer_number')
                            ->label(__('Customer number'))
                            ->default('-'),
                        TextEntry::make('cashDrawer.cash')
                            ->label(__('Cash drawer'))
                            ->default('-'),
                        TextEntry::make('paymentMethod.name')
                            ->label(__('Payment method')),
                        TextEntry::make('note')
                            ->label(__('Note')),
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
