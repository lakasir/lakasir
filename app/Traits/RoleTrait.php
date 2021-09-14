<?php

namespace App\Traits;

use App\Consntanta\RoleVariable;

/**
 * Trait RoleTrait
 * @author Sheenazien8
 */
trait RoleTrait
{
    public function resources()
    {
        return RoleVariable::RESOURCES;
    }

    public  function prefixPermission()
    {
        return 'role';
    }
}
