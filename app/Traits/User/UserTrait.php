<?php

namespace App\Traits\User;

use App\Consntanta\UserVariable;

/**
 * Trait UserTrait
 * @author sheenazien8
 */
trait UserTrait
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
        return UserVariable::RESOURCES;
    }
}
