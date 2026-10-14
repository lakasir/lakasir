<?php

return [
    'key' => [
        'currency',
        'selling_method',
        'cash_drawer_enabled',
        'secure_initial_price_enabled',
        'secure_initial_price_using_pin',
        'default_tax',
        'minimum_stock_nofication',
    ],

    'default_currency' => 'EGP',
    'default_locale' => 'ar',
    'default_timezone' => 'Africa/Cairo',

    'currencies' => [
        'EGP' => 'جنيه مصري (EGP)',
        'USD' => 'دولار أمريكي (USD)',
        'SAR' => 'ريال سعودي (SAR)',
        'AED' => 'درهم إماراتي (AED)',
        'EUR' => 'يورو (EUR)',
        'IDR' => 'روبية إندونيسية (IDR)',
    ],
];
