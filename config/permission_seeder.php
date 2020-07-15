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

        'create-supplier' => ['owner', 'employee'],
        'browse-supplier' => ['owner', 'employee'],
        'delete-supplier' => ['owner', 'employee'],
        'update-supplier' => ['owner', 'employee'],
        'bulk-delete-supplier' => ['owner', 'employee'],

        'create-group' => ['owner', 'employee'],
        'browse-group' => ['owner', 'employee'],
        'delete-group' => ['owner', 'employee'],
        'update-group' => ['owner', 'employee'],
        'bulk-delete-group' => ['owner', 'employee'],
    ]
];
