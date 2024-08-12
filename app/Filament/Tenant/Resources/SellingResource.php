<?php

namespace App\Filament\Tenant\Resources;

use App\Features\ProductInitialPrice;
use App\Filament\Tenant\Resources\SellingResource\Pages;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Selling;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
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

    public static function getBreadcrumb(): string
    {
        return __('Selling History');
    }

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
                    ->dateTime(timezone: Profile::get()->timezone)
                    ->translateLabel(),
                TextColumn::make('grand_total_price')
                    ->translateLabel()
                    ->sortable()
                    ->money(Setting::get('currency', 'IDR')),
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
            ->header(view('filament.tenant.resources.sellings.headers.overview', [
                'start_date' => request()->input('tableFilters.date.start_date'),
                'end_date' => request()->input('tableFilters.date.end_date'),
            ]))
            ->filters([
                SelectFilter::make('user_id')
                    ->label(__('Cashier'))
                    ->options(User::all()->mapWithKeys(fn (User $user) => [$user->id => $user->cashier_name])),
                Filter::make('date')
                    ->form([
                        DatePicker::make('start_date')
                            ->native(false)
                            ->format('Y-m-d')
                            ->timezone(Profile::get()->timezone)
                            ->date()
                            ->closeOnDateSelection(),
                        DatePicker::make('end_date')
                            ->native(false)
                            ->format('Y-m-d')
                            ->timezone(Profile::get()->timezone)
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
                        if ($timezone = Profile::get()->timezone) {
                            if (! $startDate && ! $endDate) {
                                return $query;
                            }
                            $startDate = Carbon::parse($startDate, $timezone)->setTimezone('UTC');
                            $endDate = Carbon::parse($endDate, $timezone)->addDay()->setTimezone('UTC');
                        }

                        return $query
                            ->when($startDate && $endDate, fn (Builder $builder) => $builder->whereBetween('date', [$startDate, $endDate]));
                    }),
            ])
            ->deferFilters();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellings::route('/'),
            'view' => Pages\ViewSelling::route('/{record}'),
        ];
    }
}
