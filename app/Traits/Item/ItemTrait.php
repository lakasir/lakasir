<?php

namespace App\Traits\Item;

use App\Consntanta\ItemVariable;

/**
 * Trait ItemTrait
 * @author sheenazien8
 */
trait ItemTrait
{
    /**
     * prefixPermission
     *
     * @access protected
     * @return string
     */
    protected function prefixPermission(): string
    {
        return 'item';
    }

    /**
     * resources
     *
     * @access protected
     * @return string
     */
    protected function resources(): string
    {
        return ItemVariable::RESOURCES;
    }
}
