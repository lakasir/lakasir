<?php

namespace Modules\Warehouse;

use Illuminate\Support\Facades\Event;
use Lakasir\LakasirModule\Events\ProductCreated;
use Lakasir\LakasirModule\Events\ProductUpdated;
use Lakasir\LakasirModule\Providers\ExtendModuleServiceProvider;
use Modules\Warehouse\Models\ProductionRecipeItem;

class WarehouseServiceProvider extends ExtendModuleServiceProvider
{
    public function register()
    {
        parent::register();

        Event::listen([ProductCreated::class, ProductUpdated::class], function (ProductCreated|ProductUpdated $event) {
            $recipes = $event->data[getPackageModuleName('Warehouse')]['recipes'];
            ProductionRecipeItem::whereProductId($event->product->id)->delete();

            foreach ($recipes as $recipe) {
                ProductionRecipeItem::query()->create([
                    'product_id' => $event->product->id,
                    'quantity_required' => $recipe['quantity_required'],
                    'raw_material_id' => $recipe['raw_material_id'],
                ]);
            }
        });
    }

    public static function getTitle(): string
    {
        return __('Warehouse::app.title');
    }
}
