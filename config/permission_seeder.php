<?php

return
[
    'role' => ['owner', 'employee'],
    'permissions' => [
        // add company permission
        'create-company' => ['owner'],
        'browse-company' => ['owner'],
        'delete-company' => ['owner'],
        'update-company' => ['owner'],
        'bulk-delete-company' => ['owner'],

        'create-customer_type' => ['owner', 'employee'],
        'browse-customer_type' => ['owner', 'employee'],
        'delete-customer_type' => ['owner', 'employee'],
        'update-customer_type' => ['owner', 'employee'],
        'bulk-delete-customer_type' => ['owner', 'employee'],

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

        'create-customer' => ['owner', 'employee'],
        'browse-customer' => ['owner', 'employee'],
        'delete-customer' => ['owner', 'employee'],
        'update-customer' => ['owner', 'employee'],
        'bulk-delete-customer' => ['owner', 'employee'],

        'create-customer-point' => ['owner', 'employee'],

        'create-grouping' => ['owner', 'employee'],
        'browse-grouping' => ['owner', 'employee'],
        'delete-grouping' => ['owner', 'employee'],
        'update-grouping' => ['owner', 'employee'],
        'bulk-delete-grouping' => ['owner', 'employee'],

        'create-purchasing' => ['owner', 'employee'],
        'browse-purchasing' => ['owner', 'employee'],
        'delete-purchasing' => ['owner', 'employee'],
        'update-purchasing' => ['owner', 'employee'],
        'update-paid-purchasing' => ['owner', 'employee'],
        'bulk-delete-purchasing' => ['owner', 'employee'],

        'create-selling' => ['owner', 'employee'],
        'browse-selling' => ['owner', 'employee'],
        'delete-selling' => ['owner', 'employee'],
        'update-selling' => ['owner', 'employee'],
        'bulk-delete-selling' => ['owner', 'employee'],

        'create-user' => ['owner', 'employee'],
        'browse-user' => ['owner', 'employee'],
        'delete-user' => ['owner', 'employee'],
        'update-user' => ['owner', 'employee'],
        'bulk-delete-user' => ['owner', 'employee'],

        'create-role' => ['owner', 'employee'],
        'browse-role' => ['owner', 'employee'],
        'delete-role' => ['owner', 'employee'],
        'update-role' => ['owner', 'employee'],
        'bulk-delete-role' => ['owner', 'employee'],

        'create-payment_method' => ['owner', 'employee'],
        'browse-payment_method' => ['owner', 'employee'],
        'delete-payment_method' => ['owner', 'employee'],
        'update-payment_method' => ['owner', 'employee'],
        'bulk-delete-payment_method' => ['owner', 'employee'],

        'create-profile' => ['owner', 'employee'],
        'browse-profile' => ['owner', 'employee'],

        'browse-bill_purchasing' => ['owner', 'employee'],
        'paid-bill_purchasing' => ['owner', 'employee'],

        'browse-general-setting' => ['owner'],
    ]
];
