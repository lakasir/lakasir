<?php

namespace App\Filament\Tenant\Resources\PurchasingResource\RelationManagers;

use App\Constants\PurchasingStatus;
use App\Filament\Tenant\Resources\PurchasingResource\Traits\HasPurchasingForm;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Stock;
use App\Services\Tenants\PurchasingService;
use App\Services\Tenants\StockService;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Support\Number;

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
        $disabled = $this->getOwnerRecord()->status == PurchasingStatus::approved;

        return $table
            ->recordTitleAttribute('product.name')
            ->contentFooter(function () {
                return view('filament.tenant.resources.purchasings.table.footer', [
                    'total_selling_price' => Number::format($this->ownerRecord->total_selling_price),
                    'total_initial_price' => Number::format($this->ownerRecord->total_initial_price),
                ]);
            })
            ->paginated(false)
            ->columns([
                TextColumn::make('product.name')
                    ->translateLabel(),
                TextInputColumn::make('init_stock')
                    ->type('number')
                    ->translateLabel()
                    ->disabled($disabled)
                    ->afterStateUpdated(function (Stock $record, $state) {
                        $this->stockService->update($record, [
                            'init_stock' => $state,
                            'stock' => $state,
                            'product_id' => $record->product_id,
                        ]);
                        $this->purchasingService->update(
                            $this->ownerRecord->getKey(),
                            $this->purchasingService->getUpdatedPrice($this->ownerRecord)
                        );
                        $this->refreshPage();
                    }),
                TextInputColumn::make('expired')
                    ->disabled($disabled)
                    ->type('date')
                    ->translateLabel(),
                TextInputColumn::make('initial_price')
                    ->disabled($disabled)
                    ->type('number')
                    ->translateLabel(),
                TextInputColumn::make('selling_price')
                    ->disabled($disabled)
                    ->type('number')
                    ->translateLabel(),
                TextInputColumn::make('selling_price')
                    ->disabled($disabled)
                    ->type('number')
                    ->translateLabel(),
                TextColumn::make('total_initial_price')
                    ->translateLabel()
                    ->formatStateUsing(fn ($state) => Number::format($state)),
                TextColumn::make('total_selling_price')
                    ->translateLabel()
                    ->formatStateUsing(fn ($state) => Number::format($state)),
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
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->icon('heroicon-s-x-mark')
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
