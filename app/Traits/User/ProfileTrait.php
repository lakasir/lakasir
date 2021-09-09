<?php

namespace App\Traits\User;

use App\Consntanta\UserVariable;

/**
 * Trait ProfileTrait
 * @author sheenazien8
 */
trait ProfileTrait
{
    /**
     * prefixPermission
     *
     * @access protected
     * @return string
     */
    protected function prefixPermission(): string
    {
        return 'profile';
    }

    /**
     * resources
     *
     * @access protected
     * @return string
     */
    protected function resources(): string
    {
        return UserVariable::PROFILE;
    }
}

