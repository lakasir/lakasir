<?php

namespace App\Filament\Tenant\Resources\StockOpnameResource\RelationManagers;

use App\Constants\StockOpnameStatus;
use App\Filament\Tenant\Resources\StockOpnameResource\Traits\HasStockOpnameItemForm;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Models\Tenants\StockOpnameItem;
use App\Services\Tenants\StockOpnameItemService;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StockOpnameItemsRelationManager extends RelationManager
{
    use HasStockOpnameItemForm, RefreshThePage;

    protected static string $relationship = 'stockOpnameItems';

    private StockOpnameItemService $soIService;

    public function __construct()
    {
        $this->soIService = new StockOpnameItemService();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->get('product'));
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->translateLabel(),
                Tables\Columns\SelectColumn::make('adjustment_type')
                    ->options([
                        'broken' => __('Broken'),
                        'lost' => __('Lost'),
                        'expired' => __('Expired'),
                        'manual_input' => __('Manual Input'),
                    ])
                    ->translateLabel()
                    ->disabled(fn () => $this->getOwnerRecord()->status == StockOpnameStatus::approved)
                    ->afterStateUpdated(function (StockOpnameItem $soi, $state) {
                        $adjusment_stock = $soi->current_stock - $soi->amount;
                        if ($state == 'manual_input') {
                            $adjusment_stock = $soi->current_stock + $soi->amount;
                        }
                        $this->soIService->update($soi, [
                            'amount_after_adjustment' => $adjusment_stock,
                            'adjustment_type' => $state,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('current_stock')
                    ->translateLabel(),
                Tables\Columns\TextInputColumn::make('amount')
                    ->type('number')
                    ->translateLabel()
                    ->rules(function (StockOpnameItem $soI) {
                        if ($soI->adjustment_type == 'manual_input') {

                            return [];
                        }

                        return [
                            'lte:'.$soI->current_stock,
                        ];
                    })
                    ->disabled(fn () => $this->getOwnerRecord()->status == StockOpnameStatus::approved)
                    ->afterStateUpdated(function (StockOpnameItem $soi, $state) {
                        $adjusment_stock = $soi->current_stock - $state;
                        if ($soi->adjustment_type == 'manual_input') {
                            $adjusment_stock = $soi->current_stock + $state;
                        }
                        $this->soIService->update($soi, [
                            'amount' => $state,
                            'amount_after_adjustment' => $adjusment_stock,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('amount_after_adjustment')
                    ->translateLabel(),
                Tables\Columns\ImageColumn::make('attachment')
                    ->translateLabel(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public function isReadOnly(): bool
    {
        $so = $this->getOwnerRecord();

        return $so->status == StockOpnameStatus::approved;
    }
}
