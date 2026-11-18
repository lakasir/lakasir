<?php

namespace Modules\Warehouse\Filament\Resources\ProductionRecipeResource\Pages;

use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource;
use Modules\Warehouse\Filament\Resources\ProductionRecipeResource\RelationManagers\ProductionRecipeItemsRelationManager;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Services\ProductionRecipeService;

class CreateProductionRecipe extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = ProductionRecipeResource::class;

    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label(__('Product'))
                ->options(fn () => Product::query()
                    ->whereDoesntHave('productionRecipeItems')
                    ->orderBy('name')
                    ->pluck('name', 'id'))
                ->searchable()
                ->required()
                ->columnSpanFull()
                ->helperText(__('Products without a recipe yet. Add the recipe to an existing one from its own page.')),
            Repeater::make('raw_materials')
                ->label(__('Warehouse::app.form.recipes'))
                ->default([])
                ->addActionLabel(__('Add to recipes'))
                ->minItems(1)
                ->required()
                ->distinct()
                ->schema(ProductionRecipeItemsRelationManager::getDefaultForm())
                ->columnSpanFull(),
        ]);
    }

    protected function handleRecordCreation(array $data): Product
    {
        $rows = array_values(array_filter(
            $data['raw_materials'] ?? [],
            fn ($row) => ! empty($row['raw_material_id']),
        ));

        (new ProductionRecipeService())->create((int) $data['product_id'], $rows);

        return Product::findOrFail($data['product_id']);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('Recipe saved'));
    }
}
