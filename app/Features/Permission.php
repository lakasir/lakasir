<?php

namespace App\Features;

class Permission
{
    public $name = 'permission';

    public function resolve(): mixed
    {
        return true;
    }
}
