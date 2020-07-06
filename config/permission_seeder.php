<?php

return
[
    'role' => ['owner', 'employee'],
    'permissions' => [
        'create-unit' => ['owner', 'employee'],
        'browse-unit' => ['owner', 'employee'],
        'delete-unit' => ['owner', 'employee'],
        'update-unit' => ['owner', 'employee'],
        'bulk-delete-unit' => ['owner', 'employee'],

        'create-category' => ['owner', 'employee'],
        'browse-category' => ['owner', 'employee'],
        'delete-category' => ['owner', 'employee'],
        'update-category' => ['owner', 'employee'],
        'bulk-delete-category' => ['owner', 'employee'],

        'create-item' => ['owner', 'employee'],
        'browse-item' => ['owner', 'employee'],
        'delete-item' => ['owner', 'employee'],
        'update-item' => ['owner', 'employee'],
        'bulk-delete-item' => ['owner', 'employee'],
    ]
];
