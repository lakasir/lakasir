<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\RelationManagers;

use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use App\Services\Tenants\PurchasingService;
use App\Services\Tenants\StockService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class StocksRelationManager extends RelationManager
{
    use RefreshThePage;

    protected static string $relationship = 'stocks';

    private StockService $stockService;

    private PurchasingService $purchasingService;

    public function __construct()
    {
        $this->stockService = new StockService();
        $this->purchasingService = new PurchasingService();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->relationship(name: 'product', titleAttribute: 'name')
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
            TextInput::make('total_initial_price')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly(),
            TextInput::make('total_selling_price')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly(),
        ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->columns([
                TextColumn::make('product.name'),
                TextColumn::make('init_stock')
                    ->label(__('Stock')),
                TextColumn::make('initial_price')
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('selling_price')
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('total_initial_price')
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('total_selling_price')
                    ->money(Setting::get('currency', 'IDR')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Add Item'))
                    ->action(function (array $data) {
                        /** @var Purchasing $purchasing */
                        $purchasing = $this->ownerRecord;
                        $this->stockService->create($data, $purchasing);
                        $this->purchasingService->update(
                            $purchasing->getKey(),
                            $this->purchasingService->getUpdatedPrice($purchasing)
                        );
                        $this->refreshPage();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->action(function (Stock $stock, array $data) {
                        /** @var Purchasing $purchasing */
                        $purchasing = $this->ownerRecord;
                        $this->stockService->update($stock, $data);
                        $this->purchasingService->update(
                            $purchasing->getKey(),
                            $this->purchasingService->getUpdatedPrice($purchasing)
                        );
                        $this->refreshPage();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->action(function (Stock $stock) {
                        $stock->delete();
                        $purchasing = $this->ownerRecord;
                        $this->purchasingService->update(
                            $purchasing->getKey(),
                            $this->purchasingService->getUpdatedPrice($purchasing)
                        );
                        $this->refreshPage();
                    }),
            ]);

    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
