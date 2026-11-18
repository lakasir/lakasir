<?php

namespace Modules\Warehouse\Filament\Resources;

use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Lakasir\LakasirModule\Abstracts\ModuleResource;
use Modules\Warehouse\Consts\ProductionBatchType;
use Modules\Warehouse\Filament\Actions\ProductionAction;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers\ProductionRecipeItemsRelationManager;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource\Pages;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers\ProductionBathcesRelationManager;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\ProductionRecipeItem;

class ProductionRecipeResource extends ModuleResource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): ?string
    {
        return __('Warehouse::app.production_recipe.title');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function () {
                return Product::query()->whereHas('productionRecipeItems');
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->translateLabel(),
                TextColumn::make('production_batches_count')
                    ->counts([
                        'productionBatches' => fn (Builder $builder) => $builder->whereNot('status', ProductionBatchType::finished),
                    ])
                    ->label(__('Warehouse::app.production_batches.total_bacthes')),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('add_ingredient')
                    ->label(__('Add to recipe'))
                    ->icon('heroicon-o-plus')
                    ->modalHeading(fn (Product $record) => __('Add to recipe').': '.$record->name)
                    ->form(ProductionRecipeItemsRelationManager::getDefaultForm())
                    ->action(function (Product $record, array $data) {
                        // The app's Product model has no recipe relation; it
                        // lives on the module's subclass, so write the row
                        // directly rather than through a relation method that
                        // may not exist on the resolved model.
                        ProductionRecipeItem::query()->create([
                            'product_id' => $record->getKey(),
                            'raw_material_id' => $data['raw_material_id'],
                            'quantity_required' => (float) $data['quantity_required'],
                            'quantity_unit' => $data['quantity_unit'] ?? null,
                        ]);

                        Notification::make()->success()->title(__('Recipe updated'))->send();
                    }),
                ProductionAction::make(Action::class),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
        ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductionRecipeItemsRelationManager::class,
            ProductionBathcesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductionRecipes::route('/'),
            'create' => Pages\CreateProductionRecipe::route('/create'),
            'view' => Pages\ViewProductionRecipe::route('/{record}'),
        ];
    }
}
