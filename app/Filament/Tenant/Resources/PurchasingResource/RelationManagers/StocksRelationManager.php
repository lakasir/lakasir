<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\RelationManagers;

use App\Constants\PurchasingStatus;
use App\Filament\Tenant\Resources\PurchasingResource\Traits\HasPurchasingForm;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use App\Services\Tenants\PurchasingService;
use App\Services\Tenants\StockService;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class StocksRelationManager extends RelationManager
{
    use HasPurchasingForm, RefreshThePage;

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
        return $form->schema($this->get('product'))
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->columns([
                TextColumn::make('product.name')
                    ->translateLabel(),
                TextInputColumn::make('init_stock')
                    ->type('number')
                    ->translateLabel()
                    ->disabled(fn () => $this->getOwnerRecord()->status == PurchasingStatus::approved)
                    ->afterStateUpdated(function (Stock $record, $state) {
                        $this->stockService->update($record, [
                            'init_stock' => $state,
                            'stock' => $state,
                            'product_id' => $record->product_id,
                        ]);
                    }),
                TextColumn::make('expired')
                    ->translateLabel(),
                TextColumn::make('initial_price')
                    ->translateLabel()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('selling_price')
                    ->translateLabel()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('total_initial_price')
                    ->translateLabel()
                    ->money(Setting::get('currency', 'IDR')),
                TextColumn::make('total_selling_price')
                    ->translateLabel()
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
                    ->visible(fn (Purchasing $purchasing) => $purchasing->status != PurchasingStatus::approved)
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
                    ->visible(fn (Purchasing $purchasing) => $purchasing->status != PurchasingStatus::approved)
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
        $purchasing = $this->getOwnerRecord();

        return $purchasing->status == PurchasingStatus::approved;
    }
}
