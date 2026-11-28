<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\CashDrawerResource\Pages;
use App\Models\Tenants\CashDrawer;
use App\Models\Tenants\Setting;
use App\Models\Tenants\User;
use App\Services\Tenants\CashDrawerService;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\RepeatableEntry;
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

class CashDrawerResource extends Resource
{
    use HasTranslatableResource;

    protected static ?string $model = CashDrawer::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Cash Drawer History';

    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return can('read selling');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Opened at'))
                    ->dateTime(timezone: auth()->user()->profile?->timezone)
                    ->sortable(),
                TextColumn::make('openedBy.name')
                    ->label(__('Opened by'))
                    ->placeholder('-'),
                TextColumn::make('closed_at')
                    ->label(__('Closed at'))
                    ->dateTime(timezone: auth()->user()->profile?->timezone)
                    ->placeholder(__('Still open'))
                    ->sortable(),
                TextColumn::make('closedBy.name')
                    ->label(__('Closed by'))
                    ->placeholder('-'),
                TextColumn::make('cash')
                    ->label(__('Opening cash'))
                    ->money(fn (): string => Setting::get('currency', 'IDR'))
                    ->sortable(),
                TextColumn::make('counted_cash')
                    ->label(__('Counted cash'))
                    ->money(fn (): string => Setting::get('currency', 'IDR'))
                    ->placeholder('-'),
                TextColumn::make('sales_count')
                    ->label(__('Sales'))
                    ->state(fn (CashDrawer $record): int => app(CashDrawerService::class)->summary($record)['sales_count']),
                TextColumn::make('expected_cash')
                    ->label(__('Expected cash'))
                    ->state(fn (CashDrawer $record): float => app(CashDrawerService::class)->summary($record)['expected_cash'])
                    ->money(fn (): string => Setting::get('currency', 'IDR')),
                TextColumn::make('difference')
                    ->label(__('Difference'))
                    ->state(fn (CashDrawer $record): ?float => app(CashDrawerService::class)->summary($record)['difference'])
                    ->money(fn (): string => Setting::get('currency', 'IDR'))
                    ->placeholder('-')
                    ->color(fn (?float $state): string => match (true) {
                        $state === null => 'gray',
                        $state < 0 => 'danger',
                        default => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('opened_by')
                    ->label(__('Opened by'))
                    ->options(fn (): array => User::query()
                        ->get(['id', 'name', 'email'])
                        ->mapWithKeys(fn ($user): array => [$user->id => $user->cashier_name])
                        ->all()),
                Filter::make('open')
                    ->label(__('Still open'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('closed_by')),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')
                            ->label(__('From'))
                            ->native(false),
                        DatePicker::make('until')
                            ->label(__('Until'))
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('created_at', '<=', Carbon::parse($date)->addDay()));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->deferFilters();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('opening_cash')
                ->label(__('Opening cash'))
                ->state(fn (CashDrawer $record): float => app(CashDrawerService::class)->summary($record)['opening_cash'])
                ->money(fn (): string => Setting::get('currency', 'IDR')),
            TextEntry::make('cash_sales')
                ->label(__('Cash sales'))
                ->state(fn (CashDrawer $record): float => app(CashDrawerService::class)->summary($record)['cash_sales'])
                ->money(fn (): string => Setting::get('currency', 'IDR')),
            TextEntry::make('expected_cash')
                ->label(__('Expected cash'))
                ->state(fn (CashDrawer $record): float => app(CashDrawerService::class)->summary($record)['expected_cash'])
                ->money(fn (): string => Setting::get('currency', 'IDR')),
            TextEntry::make('counted_cash')
                ->label(__('Counted cash'))
                ->state(fn (CashDrawer $record): ?float => app(CashDrawerService::class)->summary($record)['counted_cash'])
                ->money(fn (): string => Setting::get('currency', 'IDR'))
                ->placeholder(__('Still open')),
            TextEntry::make('difference')
                ->label(__('Difference'))
                ->state(fn (CashDrawer $record): ?float => app(CashDrawerService::class)->summary($record)['difference'])
                ->money(fn (): string => Setting::get('currency', 'IDR'))
                ->placeholder('-'),
            RepeatableEntry::make('payment_methods')
                ->label(__('Sales per payment method'))
                ->state(fn (CashDrawer $record): array => app(CashDrawerService::class)->summary($record)['payment_methods'])
                ->schema([
                    TextEntry::make('name')
                        ->label(__('Payment method')),
                    TextEntry::make('count')
                        ->label(__('Sales')),
                    TextEntry::make('total')
                        ->label(__('Total'))
                        ->money(fn (): string => Setting::get('currency', 'IDR')),
                ])
                ->columns(3),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashDrawers::route('/'),
            'view' => Pages\ViewCashDrawer::route('/{record}'),
        ];
    }
}
