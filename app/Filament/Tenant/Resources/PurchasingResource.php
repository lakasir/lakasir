<?php

namespace App\Filament\Tenant\Resources;

use App\Constants\PurchasingStatus;
use App\Filament\Tenant\Resources\PurchasingResource\Pages;
use App\Filament\Tenant\Resources\PurchasingResource\Traits\HasPurchasingForm;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Supplier;
use App\Traits\HasTranslatableResource;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
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
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PurchasingResource extends Resource
{
    use HasPurchasingForm, HasTranslatableResource;

    protected static ?string $model = Purchasing::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    public $data;

    public static function form(Form $form): Form
    {
        $self = new self();

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
                TextInput::make('supplier_phone_number')
                    ->translateLabel()
                    ->readOnly(),
                DatePicker::make('due_date')
                    ->translateLabel()
                    ->native(false)
                    ->required(),
                DatePicker::make('date')
                    ->default(now())
                    ->translateLabel()
                    ->native(false)
                    ->required(),
                FileUpload::make('image')
                    ->translateLabel()
                    ->image(),
                TableRepeater::make('stocks')
                    ->headers([
                        Header::make('product_name')
                            ->label(__('Product name'))
                            ->width('150px'),
                        Header::make('quantity')
                            ->label(__('Quantity'))
                            ->width('150px'),
                        Header::make('expired')
                            ->label(__('Expired'))
                            ->width('150px'),
                        Header::make('initial_price')
                            ->label(__('Initial price'))
                            ->width('150px'),
                        Header::make('selling_price')
                            ->label(__('Selling price'))
                            ->width('150px'),
                        Header::make('total_initial_price')
                            ->label(__('Total initial price'))
                            ->width('150px'),
                        Header::make('total_selling_price')
                            ->label(__('Total selling price'))
                            ->width('150px'),
                    ])
                    ->schema($self->get())
                    ->orderable(false)
                    ->visibleOn(['create'])
                    ->columnSpan('full'),
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
                TextColumn::make('number')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('date')
                    ->translateLabel()
                    ->date(),
                TextColumn::make('stocks_count')
                    ->label(__('Item amounts'))
                    ->counts('stocks'),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PurchasingStatus::all()),
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
            TextEntry::make('total_initial_price')
                ->money(Setting::get('currency', 'IDR'))
                ->translateLabel(),
            TextEntry::make('total_selling_price')
                ->money(Setting::get('currency', 'IDR'))
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
