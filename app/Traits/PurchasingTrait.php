<?php

namespace App\Traits;

use App\Consntanta\PurchasingVariable;

/**
 * Trait PurchasingTrait
 * @author sheenazien8
 */
trait PurchasingTrait
{
    /**
     * prefixPermission
     *
     * @access protected
     * @return string
     */
    protected function prefixPermission(): string
    {
        return 'purchasing';
    }

    /**
     * resources
     *
     * @access protected
     * @return string
     */
    protected function resources(): string
    {
        return PurchasingVariable::RESOURCES;
    }
}
