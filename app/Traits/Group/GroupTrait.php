<?php

namespace App\Traits\Group;

use App\Consntanta\GroupVariable;

/**
 * Trait GroupTrait
 * @author sheenazien8
 */
trait GroupTrait
{
    /**
     * prefixPermission
     *
     * @access protected
     * @return string
     */
    protected function prefixPermission(): string
    {
        return 'category';
    }

    /**
     * resources
     *
     * @access protected
     * @return string
     */
    protected function resources(): string
    {
        return GroupVariable::RESOURCES;
    }
}
