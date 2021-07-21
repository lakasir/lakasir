<?php

namespace App\Traits\Customer;

use App\Consntanta\CustomerVariable;

/**
 * Trait CategoryTrait
 * @author sheenazien8
 */
trait CustomerTrait
{
    /**
     * prefixPermission
     *
     * @access protected
     * @return string
     */
    protected function prefixPermission(): string
    {
        return 'customer';
    }

    /**
     * resources
     *
     * @access protected
     * @return string
     */
    protected function resources(): string
    {
        return CustomerVariable::RESOURCES;
    }
}
