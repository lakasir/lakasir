<?php

namespace App\Features;

class Qris
{
    public $name = 'qris';

    public function resolve(): mixed
    {
        return config('services.qris.api_key') && config('services.qris.m_id');
    }
}

