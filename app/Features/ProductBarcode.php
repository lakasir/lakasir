<?php

namespace App\Features;

class ProductBarcode
{
    public $name = 'product-barcode';

    public function resolve(mixed $scope): mixed
    {
        return true;
    }
}
