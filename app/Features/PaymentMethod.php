<?php

namespace App\Features;

class PaymentMethod
{
    public $name = 'payment-method';

    public function resolve(): mixed
    {
        return true;
    }
}
