<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\PurchasingResource\Pages;
use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Supplier;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PurchasingResource extends Resource
{
    protected static ?string $model = Purchasing::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    public $data;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('supplier_id')
                    ->label(__('Supplier'))
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
                    ->label(__('Supplier Phone Number'))
                    ->readOnly(),
                DatePicker::make('due_date')
                    ->label(__('Due Date'))
                    ->native(false)
                    ->required(),
                DatePicker::make('date')
                    ->label(__('Purchasing Date'))
                    ->native(false)
                    ->required(),
                FileUpload::make('image')
                    ->label(__('Attachment'))
                    ->image(),
                TableRepeater::make('stocks')
                    ->headers([
                        Header::make('product_name')
                            ->label(__('Product Name'))
                            ->width('150px'),
                        Header::make('quantity')
                            ->label(__('Quantity'))
                            ->width('150px'),
                        Header::make('initial_price')
                            ->label(__('Initial Price'))
                            ->width('150px'),
                        Header::make('selling_price')
                            ->label(__('Selling Price'))
                            ->width('150px'),
                        Header::make('total_initial_price')
                            ->label(__('Total Initial Price'))
                            ->width('150px'),
                        Header::make('total_selling_price')
                            ->label(__('Total Selling Price'))
                            ->width('150px'),
                    ])
                    ->schema([
                        Select::make('product_id')
                            ->native(false)
                            ->placeholder(__('Search...'))
                            ->relationship(name: 'stocks.product', titleAttribute: 'name')
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $product = Product::find($state);
                                if ($product) {
                                    $set('initial_price', $product->initial_price);
                                    $set('selling_price', $product->selling_price);
                                }
                            }),
                        TextInput::make('stock')
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $set('total_initial_price', Str::of($get('initial_price'))->replace(',', '')->toInteger() * (float) $state);
                                $set('total_selling_price', Str::of($get('selling_price'))->replace(',', '')->toInteger() * (float) $state);
                            })
                            ->live(onBlur: true),
                        TextInput::make('initial_price')
                            ->prefix(Setting::get('currency', 'IDR'))
                            ->mask(RawJs::make('$money($input)'))
                            ->lte('selling_price')
                            ->stripCharacters(',')
                            ->numeric()
                            ->required()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $set('total_initial_price', Str::of($state)->replace(',', '')->toInteger() * $get('stock'));
                            })
                            ->live(onBlur: true),
                        TextInput::make('selling_price')
                            ->prefix(Setting::get('currency', 'IDR'))
                            ->mask(RawJs::make('$money($input)'))
                            ->gte('initial_price')
                            ->stripCharacters(',')
                            ->numeric()
                            ->required()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $set('total_selling_price', Str::of($state)->replace(',', '')->toInteger() * $get('stock'));
                            })
                            ->live(onBlur: true),
                        TextInput::make('total_selling_price')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->readOnly(),
                        TextInput::make('total_initial_price')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->readOnly(),
                    ])
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
                    ->searchable(),
                TextColumn::make('number')
                    ->searchable(),
                TextColumn::make('date')
                    ->label(__('Selling Date'))
                    ->date(),
                TextColumn::make('item_amounts'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('supplier.name')
                ->label(__('Supplier Name')),
            TextEntry::make('supplier.phone_number')
                ->label(__('Supplier Contact')),
            TextEntry::make('due_date')
                ->date()
                ->label(__('Due Date')),
            TextEntry::make('date')
                ->date()
                ->label(__('Purchasing Date')),
            TextEntry::make('total_initial_price')
                ->money(Setting::get('currency', 'IDR'))
                ->label(__('Total Initial Price')),
            TextEntry::make('total_selling_price')
                ->money(Setting::get('currency', 'IDR'))
                ->label(__('Total Selling Price')),
            ImageEntry::make('image')
                ->label(__('Attachment')),
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
