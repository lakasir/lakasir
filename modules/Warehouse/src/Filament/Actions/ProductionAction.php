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
use Modules\Warehouse\Support\Money;

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
                $qty = max(1, (int) $data['quantity_produced']);

                $porsiCost = 0;

                foreach ($product->productionRecipeItems as $productionRecipeItem) {
                    $porsiCost += $productionRecipeItem->rawMaterial?->costFor(
                        (float) $productionRecipeItem->quantity_required,
                        $productionRecipeItem->quantity_unit ?? $productionRecipeItem->rawMaterial->base_unit,
                    ) ?? 0;
                }

                ProductionBatch::create(array_merge($data, [
                    'product_id' => $product->id,
                    'quantity_produced' => $qty,
                    'total_cost' => $porsiCost * $qty,
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
        // Cost of the recipe for ONE produced unit.
        $porsiCost = 0;

        /** @var ProductionRecipeItem $productionRecipeItem */
        foreach ($product->productionRecipeItems as $productionRecipeItem) {
            $porsiCost += $productionRecipeItem->rawMaterial?->costFor(
                (float) $productionRecipeItem->quantity_required,
                $productionRecipeItem->quantity_unit ?? $productionRecipeItem->rawMaterial->base_unit,
            ) ?? 0;
        }

        return [
            TextInput::make('quantity_produced')
                ->label(__('Warehouse::app.production_batches.quantity_produced'))
                ->helperText(__('Warehouse::app.production_batches.quantity_produced_placeholder'))
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->live(onBlur: true)
                ->suffix(str($product->unit)->upper())
                ->required(),
            Split::make([
                PriceInput::make('total_cost')
                    ->readOnly()
                    ->default($porsiCost)
                    ->formatStateUsing(fn ($state) => $state ?? $porsiCost)
                    ->helperText(__('Recipe cost for the whole batch at the quantity produced'))
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
            Placeholder::make('hpp_placeholder_content')
                ->content(function (Get $get) use ($porsiCost, $product) {
                    $qty = max(1, (int) $get('quantity_produced'));
                    $batchCost = ($porsiCost * $qty) + Money::toFloat($get('added_cost'));

                    return new HtmlString(
                        "<p class='text-xl font-semibold'>".price_format($batchCost / $qty)
                        .' <span class="text-sm font-normal">/ '.strtoupper((string) $product->unit).'</span></p>'
                    );
                })
                ->label(__('HPP per unit')),
            Placeholder::make('total_cost_placeholder_content')
                ->content(function (Get $get) use ($porsiCost, $product) {
                    $qty = max(1, (int) $get('quantity_produced'));
                    $totalCost = ($porsiCost * $qty) + Money::toFloat($get('added_cost'));
                    $hpp = $totalCost / $qty;
                    $estimationSellingPrice = $hpp * (1 + (float) $get('margin') / 100);

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
