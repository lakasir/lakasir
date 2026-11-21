<?php

return [
    /*
     * The default selling method, used when a shop has not set one:
     * fifo, lifo or normal. Read through config() so `php artisan config:cache`
     * keeps working.
     */
    'selling_method' => env('SELLING_METHOD', 'fifo'),

    'key' => [
        'currency',
        'selling_method',
        'cash_drawer_enabled',
        'secure_initial_price_enabled',
        'secure_initial_price_using_pin',
        'default_tax',
        'minimum_stock_nofication',
    ],
];
