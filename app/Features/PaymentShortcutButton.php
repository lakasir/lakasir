<?php

namespace App\Features;

class PaymentShortcutButton
{
    public $name = 'payment-shortcut-button';

    public function resolve(mixed $scope): mixed
    {
        return true;
    }
}
