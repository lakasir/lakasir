<?php

namespace App\Traits\Supplier;

use App\Consntanta\SupplierVariable;

/**
 * Trait SupplierTrait
 *
 */
trait SupplierTrait
{
    /**
     * prefixPermission
     *
     * @access protected
     * @return string
     */
    protected function prefixPermission(): string
    {
        return 'supplier';
    }

    /**
     * resources
     *
     * @access protected
     * @return string
     */
    protected function resources(): string
    {
        return SupplierVariable::RESOURCES;
    }
}
