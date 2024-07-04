<?php

namespace App\Features;

class ProductInitialPrice
{
    public $name = 'product-initial-price';

    public function resolve(mixed $scope): mixed
    {
        return true;
    }
}
