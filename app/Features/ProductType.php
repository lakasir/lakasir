<?php

namespace App\Features;

class ProductType
{
    public $name = 'product-type';

    public function resolve(mixed $scope): mixed
    {
        return true;
    }
}
