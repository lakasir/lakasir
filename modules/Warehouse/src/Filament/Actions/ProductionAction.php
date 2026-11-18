<?php

namespace Modules\Warehouse\Filament\Actions;

use App\Filament\Tenant\Components\PriceInput;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Tables\Actions\Action as ActionsAction;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\HtmlString;
use Modules\Warehouse\Models\Product;
use Modules\Warehouse\Models\ProductionBatch;
use Modules\Warehouse\Models\ProductionRecipeItem;

class ProductionAction
{
    /**
     * @param  Action|ActionsAction  $action
     *
     * @throws BindingResolutionException
     */
    public static function make(string $action): Action|ActionsAction
    {
        return $action::make('production')
            ->icon('heroicon-s-megaphone')
            ->label(__('Warehouse::app.production_recipe.production'))
            ->modal()
            ->form(function (Product $product) {
                return self::form($product);
            })
            ->action(function (array $data, Product $product) {
                ProductionBatch::create(array_merge($data, [
                    'product_id' => $product->id,
                ]));

                notification()->title(__('Warehouse::app.production_batches.succcess'));
            })
            ->closeModalByClickingAway(false);
    }

    protected static function roundUpToNearest500(float $price): float
    {
        return ceil($price / 500) * 500;
    }

    public static function form(Product $product): array
    {
        $praTotalCost = 0;

        /** @var ProductionRecipeItem $productionRecipeItem */
        foreach ($product->productionRecipeItems as $productionRecipeItem) {
            $costPerRawMaterial = $productionRecipeItem->rawMaterial->price_per_unit * $productionRecipeItem->quantity_required;
            $praTotalCost += $costPerRawMaterial;
        }

        return [
            TextInput::make('quantity_produced')
                ->label(__('Warehouse::app.production_batches.quantity_produced'))
                ->helperText(__('Warehouse::app.production_batches.quantity_produced_placeholder'))
                ->numeric()
                ->suffix(str($product->unit)->upper())
                ->required(),
            Split::make([
                PriceInput::make('total_cost')
                    ->readOnly()
                    ->default($praTotalCost)
                    ->label(__('Warehouse::app.production_batches.total_cost_placeholder')),
                PriceInput::make('added_cost')
                    ->label(__('Warehouse::app.production_batches.added_cost'))
                    ->numeric()
                    ->helperText(__('Warehouse::app.production_batches.added_cost_placeholder')),
                TextInput::make('margin')
                    ->label(__('Warehouse::app.production_batches.margin'))
                    ->helperText(__('Warehouse::app.production_batches.margin_placeholder'))
                    ->required()
                    ->minValue(1)
                    ->suffix('%')
                    ->numeric(),
            ])->live(),
            Toggle::make('complete_price')
                ->live()
                ->label(__('Warehouse::app.production_batches.complete_price_placeholder')),
            Placeholder::make('total_cost_placeholder_content')
                ->content(function (Get $get) use ($praTotalCost) {
                    $totalCost = $praTotalCost + price_string_to_float($get('added_cost') ?? '0');
                    $estimationSellingPrice = $totalCost + ($totalCost * (float) $get('margin') / 100);

                    if ($get('complete_price')) {
                        $estimationSellingPrice = self::roundUpToNearest500($estimationSellingPrice);
                    }

                    $priceFormat = price_format($estimationSellingPrice);

                    return new HtmlString("<p class='text-3xl font-semibold'>{$priceFormat}</p>");
                })
                ->label(__('Warehouse::app.production_batches.estimation_selling_price')),
        ];
    }
}
