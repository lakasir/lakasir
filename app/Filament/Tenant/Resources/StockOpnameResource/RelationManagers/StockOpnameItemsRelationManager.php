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
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->translateLabel(),
                Tables\Columns\SelectColumn::make('adjustment_type')
                    ->options([
                        'broken' => __('Broken'),
                        'lost' => __('Lost'),
                        'expired' => __('Expired'),
                        'manual_input' => __('Manual Input'),
                        'match' => __('Match'),
                    ])
                    ->translateLabel()
                    ->disabled(fn () => $this->getOwnerRecord()->status == StockOpnameStatus::approved)
                    ->afterStateUpdated(function (StockOpnameItem $soi, $state) {
                        $adjusment_stock = $soi->current_stock - $soi->actual_stock;

                        $this->soIService->update($soi, [
                            'missing_stock' => $adjusment_stock,
                            'adjustment_type' => $state,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('current_stock')
                    ->translateLabel(),
                Tables\Columns\TextInputColumn::make('actual_stock')
                    ->type('number')
                    ->translateLabel()
                    ->disabled(fn () => $this->getOwnerRecord()->status == StockOpnameStatus::approved)
                    ->afterStateUpdated(function (StockOpnameItem $soi, $state) {
                        $adjusment_stock = $soi->current_stock - $state;
                        $this->soIService->update($soi, [
                            'actual_stock' => $state,
                            'missing_stock' => $adjusment_stock,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('missing_stock')
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
