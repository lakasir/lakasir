<?php

namespace Modules\Warehouse;

use Lakasir\LakasirModule\Providers\ExtendModuleServiceProvider;

/**
 * Recipes are now managed entirely from the Production Recipes menu
 * (`/member/modules/warehouse/production-recipe`), not from the product form.
 *
 * This provider used to listen for ProductCreated/ProductUpdated and
 * delete-and-recreate every recipe line from the event payload. With the
 * product form no longer carrying recipe state that listener would wipe a
 * product's recipe on every save, so it is gone.
 */
class WarehouseServiceProvider extends ExtendModuleServiceProvider
{
    public function register()
    {
        parent::register();
    }

    public static function getTitle(): string
    {
        return __('Warehouse::app.title');
    }
}
