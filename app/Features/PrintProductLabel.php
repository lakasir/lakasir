<?php

namespace App\Features;

class PrintProductLabel
{
    public $name = 'print-product-label';

    public function resolve(mixed $scope): mixed
    {
        return true;
    }
}
