<?php
return [
    'image' => [
        'empty' => '/assets/shop.png'
    ],
    'profile' => [
        'image_empty' => '/assets/man-2.png'
    ],
    'menu' => [
        [
            'title' => 'app.settings.general.company.title',
            'description' => 'app.settings.general.company.description',
            'url' => [
                'route' => 's.general.company.index'
            ],
            'icon' => 'fa fa-lg fa-building'
        ],
        [
            'title' => 'app.settings.general.date.title',
            'description' => 'app.settings.general.date.description',
            'url' => [
                'route' => 's.general.date.index'
            ],
            'icon' => 'far fa-lg fa-calendar'
        ],
        [
            'title' => 'app.settings.general.currency.title',
            'description' => 'app.settings.general.currency.description',
            'url' => [
                'route' => 's.general.currency.index'
            ],
            'icon' => 'fab fa-lg fa-btc'
        ],
        [
            'title' => 'app.settings.general.appearance.title',
            'description' => 'app.settings.general.appearance.description',
            'url' => [
                'route' => 's.general.appearance.index'
            ],
            'icon' => 'fa fa-lg fa-fill-drip'
        ],
        [
            'title' => 'app.settings.general.plugins.title',
            'description' => 'app.settings.general.plugins.description',
            'url' => [
                'route' => 's.general.plugins.index'
            ],
            'icon' => 'fas fa-lg fa-palette'
        ],
    ],
];
