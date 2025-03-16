<?php

namespace App\Filament\Tenant\Resources;

use App\Constants\PurchasingStatus;
use App\Filament\Tenant\Resources\PurchasingResource\Pages;
use App\Filament\Tenant\Resources\PurchasingResource\Traits\HasPurchasingForm;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Supplier;
use App\Services\Tenants\PurchasingService;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class PurchasingResource extends Resource
{
    use HasPurchasingForm, HasTranslatableResource;

    protected static ?string $model = Purchasing::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    public $data;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('supplier_id')
                    ->relationship(name: 'supplier', titleAttribute: 'name')
                    ->translateLabel()
                    ->native(false)
                    ->required()
                    ->createOptionForm(Supplier::form())
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('supplier_phone_number', Supplier::find($state)?->phone_number ?? ''))
                    ->live(),
                Select::make('payment_method_id')
                    ->relationship(name: 'paymentMethod', titleAttribute: 'name')
                    ->translateLabel()
                    ->required(),
                TextInput::make('supplier_phone_number')
                    ->translateLabel()
                    ->readOnly(),
                DatePicker::make('date')
                    ->closeOnDateSelection()
                    ->default(now())
                    ->translateLabel()
                    ->native(false)
                    ->required(),
                DatePicker::make('due_date')
                    ->translateLabel()
                    ->closeOnDateSelection()
                    ->native(false)
                    ->required(),
                FileUpload::make('image')
                    ->translateLabel()
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('supplier.name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('paymentMethod.name')
                    ->default('-')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('number')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('date')
                    ->translateLabel()
                    ->date(),
                TextColumn::make('stocks_count')
                    ->label(__('Item amounts'))
                    ->counts('stocks'),
                TextColumn::make('approved_at')
                    ->dateTime(timezone: Profile::get()->timezone)
                    ->translateLabel(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (bool $state): string => match ($state) {
                        false => 'gray',
                        true => 'success',
                    })
                    ->formatStateUsing(fn ($state) => $state ? __('Paid') : __('Unpaid'))
                    ->translateLabel(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        PurchasingStatus::pending => 'gray',
                        PurchasingStatus::reviewing => 'warning',
                        PurchasingStatus::approved => 'success',
                    })
                    ->translateLabel(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Action::make('set_paid')
                        ->translateLabel()
                        ->label(function (Purchasing $purchasing) {
                            return $purchasing->payment_status ? 'Set unpaid' : 'Set paid';
                        })
                        ->action(function (Purchasing $purchasing) {
                            $purchasing->payment_status = ! $purchasing->payment_status;
                            $purchasing->save();
                        })
                        ->icon('heroicon-s-pencil-square'),
                    Action::make('update_status')
                        ->form([
                            Select::make('status')
                                ->required()
                                ->options(Arr::where(PurchasingStatus::all()->toArray(), function ($key) {
                                    if ($key == PurchasingStatus::approved) {
                                        return can('approve purchasing');
                                    }

                                    return true;
                                })),
                        ])
                        ->action(function ($data, Purchasing $purchasing, PurchasingService $purchasingService) {
                            $purchasingService->updateStatus($purchasing, $data['status']);
                        })
                        ->icon('heroicon-s-pencil-square')
                        ->visible(function (Purchasing $purchasing) {
                            return $purchasing->status != PurchasingStatus::approved;
                        }),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->button()
                    ->translateLabel(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PurchasingStatus::all()->toArray()),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    PurchasingStatus::pending => 'gray',
                    PurchasingStatus::reviewing => 'warning',
                    PurchasingStatus::approved => 'success',
                })
                ->translateLabel(),
            TextEntry::make('supplier.name')
                ->translateLabel(),
            TextEntry::make('supplier.phone_number')
                ->label(__('Supplier phone number')),
            TextEntry::make('due_date')
                ->date()
                ->translateLabel(),
            TextEntry::make('date')
                ->date()
                ->translateLabel(),
            ImageEntry::make('image')
                ->translateLabel(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchasings::route('/'),
            'create' => Pages\CreatePurchasing::route('/create'),
            'view' => Pages\ViewPurchasing::route('/{record}'),
            'edit' => Pages\EditPurchasing::route('/{record}/edit'),
        ];
    }
}
