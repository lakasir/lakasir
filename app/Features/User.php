<?php

namespace App\Features;

class User
{
    public $name = 'user';

    public function resolve(): mixed
    {
        return true;
    }
}
