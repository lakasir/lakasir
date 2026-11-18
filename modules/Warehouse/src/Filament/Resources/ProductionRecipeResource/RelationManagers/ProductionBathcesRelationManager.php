<?php

namespace Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers;

use App\Events\RecalculateEvent;
use App\Filament\Tenant\Components\PriceTextColumn;
use App\Filament\Tenant\Resources\Traits\RefreshThePage;
use App\Services\Tenants\StockService;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Warehouse\Consts\ProductionBatchType;
use Modules\Warehouse\Filament\Actions\ProductionAction;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\ProductionBatch;

class ProductionBathcesRelationManager extends RelationManager
{
    use RefreshThePage;

    protected static string $relationship = 'productionBatches';

    public function form(Form $form): Form
    {
        return $form
            ->schema(ProductionAction::form($this->ownerRecord))
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity_produced'),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => str($state)->upper())
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        ProductionBatchType::prepared => Color::Gray,
                        ProductionBatchType::started => 'primary',
                        ProductionBatchType::finished => 'success',
                    }),
                PriceTextColumn::make('total_cost'),
                PriceTextColumn::make('added_cost'),
                Tables\Columns\TextColumn::make('margin')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('complete_price')
                    ->formatStateUsing(fn ($state) => $state ? __('Yes') : __('No'))
                    ->color(fn ($state) => match ($state) {
                        0 => 'primary',
                        1 => 'success',
                    }),
                PriceTextColumn::make('estimation_selling_price'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ProductionBatchType::all()),
            ])
            ->deferFilters()
            ->actions([
                Action::make('update_status')
                    ->visible(fn ($record) => $record->status != ProductionBatchType::finished)
                    ->label(__('Warehouse::app.production_batches.update_status'))
                    ->closeModalByClickingAway(false)
                    ->form([
                        Select::make('status')
                            ->required()
                            ->default(fn ($record) => $record->status)
                            ->options(ProductionBatchType::all()),
                    ])
                    ->action(function (array $data, ProductionBatch $productionBatch, StockService $stockService) {
                        DB::transaction(function () use ($data, $productionBatch, $stockService) {
                            $productionBatch->status = $data['status'];
                            if ($data['status'] == ProductionBatchType::finished) {
                                $product = Product::find($productionBatch->product_id);
                                $qtyProduced = max(1, (int) $productionBatch->quantity_produced);
                                $hppPerUnit = ($productionBatch->total_cost + $productionBatch->added_cost) / $qtyProduced;

                                foreach ($product->productionRecipeItems as $productRecipe) {
                                    $recipeUnit = $productRecipe->quantity_unit ?? $productRecipe->rawMaterial->base_unit;
                                    $perUnit = $productRecipe->rawMaterial->toBaseUnits((float) $productRecipe->quantity_required, $recipeUnit) ?? 0;
                                    $totalMaterialUsed = $perUnit * $productionBatch->quantity_produced;
                                    $rawMaterial = $productRecipe->rawMaterial;

                                    $rawMaterial->update([
                                        'quantity' => $rawMaterial->quantity - $totalMaterialUsed,
                                    ]);
                                }
                                $stock = $stockService->create([
                                    'stock' => $productionBatch->quantity_produced,
                                    'is_ready' => true,
                                    'initial_price' => $hppPerUnit,
                                    'selling_price' => $productionBatch->estimation_selling_price,
                                    'product_id' => $product->id,
                                ]);
                                $productionBatch->stock()->associate($stock);
                                RecalculateEvent::dispatch($product, []);
                            }

                            $productionBatch->save();

                            notification()
                                ->title(__('Success'))
                                ->body(__('actions.named.update', [
                                    'name' => 'status',
                                ]))
                                ->success()
                                ->send();

                            $this->refreshPage();
                        });
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->status != ProductionBatchType::finished),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->status != ProductionBatchType::finished),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Warehouse::app.production_batches.title');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
