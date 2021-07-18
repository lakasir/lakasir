<?php

namespace App\Traits\Category;

use App\Consntanta\CategoryVariable;

/**
 * Trait CategoryTrait
 * @author sheenazien8
 */
trait CategoryTrait
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
        return CategoryVariable::RESOURCES;
    }
}
