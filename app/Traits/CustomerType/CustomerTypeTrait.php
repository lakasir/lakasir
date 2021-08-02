<?php

namespace App\Traits\CustomerType;

use App\Consntanta\CustomerTypeVariable;

/**
 * Trait CustomerTypeTrait
 * @author sheenazien8
 */
trait CustomerTypeTrait
{
    /**
     * prefixPermission
     *
     * @access protected
     * @return string
     */
    protected function prefixPermission(): string
    {
        return 'customer_type';
    }

    /**
     * resources
     *
     * @access protected
     * @return string
     */
    protected function resources(): string
    {
        return CustomerTypeVariable::RESOURCES;
    }
}
