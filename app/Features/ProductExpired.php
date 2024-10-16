<?php

namespace App\Features;

class ProductExpired
{
    public $name = 'product-expired';

    public function resolve(mixed $scope): mixed
    {
        return false;
    }
}
