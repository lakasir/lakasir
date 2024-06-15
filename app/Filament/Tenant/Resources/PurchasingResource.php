<?php

namespace App\Filament\Tenant\Resources;

use App\Constants\PurchasingStatus;
use App\Filament\Tenant\Resources\PurchasingResource\Pages;
use App\Filament\Tenant\Resources\PurchasingResource\Traits\HasPurchasingForm;
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
use Filament\Tables\Table;

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
                    ->translateLabel()
                    ->options(Supplier::pluck('name', 'id'))
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('phone_number')
                            ->rule('regex:/^(\+?\d{1,3}[-.\s]?)?(\(?\d{3}\)?[-.\s]?)?\d{3}[-.\s]?\d{4}$/')
                            ->required(),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $category = new Supplier();
                        $category->fill($data);
                        $category->save();

                        return $category->getKey();
                    })
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
            ]);
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
