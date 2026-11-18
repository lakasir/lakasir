<?php

namespace App\Filament\Tenant\Resources;

use App\Constants\StockOpnameStatus;
use App\Filament\Tenant\Resources\StockOpnameResource\Pages;
use App\Filament\Tenant\Resources\StockOpnameResource\Traits\HasStockOpnameItemForm;
use App\Models\Tenants\Profile;
use App\Models\Tenants\StockOpname;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class StockOpnameResource extends Resource
{
    use HasStockOpnameItemForm, HasTranslatableResource;

    protected static ?string $model = StockOpname::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('pic')
                    ->required()
                    ->readOnly()
                    ->default(auth()->user()->name)
                    ->label(__('PIC')),
                DatePicker::make('date')
                    ->required()
                    ->default(now())
                    ->closeOnDateSelection()
                    ->native(false)
                    ->label(__('Date')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('number')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('stock_opname_items_count')
                    ->label(__('Item amounts'))
                    ->counts('stockOpnameItems'),
                TextColumn::make('date')
                    ->date(),
                TextColumn::make('approved_at')
                    ->dateTime(timezone: Profile::get()->timezone),
                TextColumn::make('status')
                    ->translateLabel()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        StockOpnameStatus::pending => 'gray',
                        StockOpnameStatus::reviewing => 'warning',
                        StockOpnameStatus::approved => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StockOpnameStatus::all()->toArray()),
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
            ->deferFilters()
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    StockOpnameStatus::pending => 'gray',
                    StockOpnameStatus::reviewing => 'warning',
                    StockOpnameStatus::approved => 'success',
                })
                ->translateLabel(),
            TextEntry::make('pic')
                ->translateLabel(),
            TextEntry::make('date')
                ->date()
                ->translateLabel(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockOpnames::route('/'),
            'create' => Pages\CreateStockOpname::route('/create'),
            'view' => Pages\ViewStockOpname::route('/{record}'),
            'edit' => Pages\EditStockOpname::route('/{record}/edit'),
        ];
    }
}
