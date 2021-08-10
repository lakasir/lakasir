<?php

namespace App\Traits\PaymentMethod;

use App\Consntanta\PaymentMethodVariable;

/**
 * Trait GroupTrait
 * @author sheenazien8
 */
trait PaymentMethodTrait
{
    /**
     * prefixPermission
     *
     * @access protected
     * @return string
     */
    protected function prefixPermission(): string
    {
        return 'payment_method';
    }

    /**
     * resources
     *
     * @access protected
     * @return string
     */
    protected function resources(): string
    {
        return PaymentMethodVariable::RESOURCES;
    }
}
