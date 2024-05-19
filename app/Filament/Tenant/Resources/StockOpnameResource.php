<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\StockOpnameResource\Pages;
use App\Models\Tenants\Product;
use App\Models\Tenants\StockOpname;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StockOpnameResource extends Resource
{
    protected static ?string $model = StockOpname::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('pic')
                    ->required()
                    ->label(__('PIC')),
                DatePicker::make('date')
                    ->required()
                    ->native(false)
                    ->label(__('Date')),
                TableRepeater::make('stock_opname_items')
                    ->headers([
                        Header::make('product_name')
                            ->label(__('Product Name'))
                            ->width('150px'),
                        Header::make('current_stock')
                            ->label(__('Current Stock'))
                            ->width('150px'),
                        Header::make('adjustment_type')
                            ->label(__('Adjustment Type'))
                            ->width('150px'),
                        Header::make('amount')
                            ->label(__('Amount'))
                            ->width('150px'),
                        Header::make('amount_after_adjustment')
                            ->label(__('Amount After Adjustment'))
                            ->width('150px'),
                        Header::make('total_selling_price')
                            ->label(__('Total Selling Price'))
                            ->width('150px'),
                    ])
                    ->schema([
                        Select::make('product_id')
                            ->required()
                            ->native(false)
                            ->placeholder(__('Search...'))
                            ->relationship(name: 'stockOpnameItems.product', titleAttribute: 'name')
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $product = Product::find($state);
                                if ($product) {
                                    $set('current_stock', $product->stock);
                                }
                            }),
                        TextInput::make('current_stock')
                            ->readOnly()
                            ->numeric(),
                        Select::make('adjustment_type')
                            ->default('broken')
                            ->options([
                                'broken' => __('Broken'),
                                'lost' => __('Lost'),
                                'expired' => __('Expired'),
                                'manual_input' => __('Manual Input'),
                            ]),
                        TextInput::make('amount')
                            ->required()
                            ->lte('current_stock')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                $product = Product::find($get('product_id'));
                                if (! $product) {
                                    Notification::make()
                                        ->title(__('Please select the product first'))
                                        ->warning()
                                        ->send();
                                    $set('amount', 0);

                                    return;
                                }

                                $set('amount_after_adjustment', $product->stock - $state);
                            })
                            ->numeric(),
                        TextInput::make('amount_after_adjustment')
                            ->readOnly()
                            ->numeric(),
                        FileUpload::make('attachment')
                            ->maxWidth(10)
                            ->image(),
                    ])
                    ->orderable(false)
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('number')
                    ->searchable(),
                TextColumn::make('items_count')
                    ->counts([
                        'stockOpnameItems' => fn (Builder $builder) => $builder,
                    ]),
                TextColumn::make('date')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
