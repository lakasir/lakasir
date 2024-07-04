<?php

namespace App\Features;

class ProductStock
{
    public $name = 'product-stock';

    public function resolve(mixed $scope): mixed
    {
        return true;
    }
}
