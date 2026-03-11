<?php

return [
    'save' => 'Save Changes',
    'saving' => 'Saving...',
    'open_advanced' => 'Open Advanced Settings',

    'messages' => [
        'saved' => 'Settings have been saved successfully.',
        'save_failed' => 'Unable to save settings right now. Please try again.',
    ],

    'groups' => [
        'general' => 'General',
        'system' => 'System',
    ],

    'nav' => [
        'general' => 'General',
        'users' => 'Users',
        'roles' => 'Roles',
        'printer' => 'Printer',
        'about' => 'About',
        'profile' => 'Profile',
    ],

    'landing' => [
        'title' => 'Setting',
        'sections' => [
            'general' => 'General',
            'system' => 'System',
        ],
        'items' => [
            'category' => 'Category',
            'currency' => 'Currency',
            'language' => 'Language',
            'display' => 'Display',
        ],
        'actions' => [
            'cancel' => 'Cancel',
            'set' => 'Set',
        ],
        'currencies' => [
            'idr' => 'IDR',
            'usd' => 'USD',
        ],
        'languages' => [
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
            'es' => 'Spanish',
        ],
        'displays' => [
            '58mm' => '58mm Paper',
            '80mm' => '80mm Paper',
        ],
    ],

    'category' => [
        'title' => 'Category',
        'search' => 'Search',
        'add_button' => 'Add Category',
        'add_title' => 'Add Category',
        'edit_title' => 'Edit Category',
        'input_placeholder' => 'Enter category name',
        'save' => 'Save',
        'empty' => 'No categories found.',
        'delete_confirm' => 'Delete this category?',
        'messages' => [
            'deleted' => 'Category has been deleted successfully.',
            'delete_blocked' => 'Category cannot be deleted because it is used by products.',
        ],
    ],

    'general' => [
        'subtitle' => 'Configure store information, receipts, tax, and transaction defaults.',
        'sections' => [
            'store_information' => 'Store Information',
            'receipt_settings' => 'Receipt Settings',
            'currency_settings' => 'Currency Settings',
            'tax_settings' => 'Tax Settings',
            'transaction_settings' => 'Transaction Settings',
        ],
        'fields' => [
            'store_name' => 'Store Name',
            'store_address' => 'Store Address',
            'store_phone' => 'Store Phone',
            'store_email' => 'Store Email',
            'receipt_header' => 'Receipt Header',
            'receipt_footer' => 'Receipt Footer',
            'show_logo_on_receipt' => 'Show logo on receipt',
            'paper_size' => 'Paper Size',
            'currency_symbol' => 'Currency Symbol',
            'currency_position' => 'Currency Position',
            'decimal_places' => 'Decimal Places',
            'enable_tax' => 'Enable tax',
            'tax_rate' => 'Tax Rate (%)',
            'tax_included' => 'Tax included in price',
            'default_payment_method' => 'Default Payment Method',
            'require_customer_info' => 'Require customer information',
            'receipt_auto_print' => 'Auto print receipt',
        ],
        'options' => [
            'before' => 'Before amount',
            'after' => 'After amount',
            'cash' => 'Cash',
            'transfer' => 'Bank Transfer',
            'card' => 'Card',
        ],
    ],

    'users' => [
        'title' => 'Users',
        'subtitle' => 'Review your current users and jump to advanced user management.',
        'add_user' => 'Add User',
        'manage_all' => 'Manage All Users',
        'empty_title' => 'No users found',
        'empty_description' => 'Create your first user to start delegating access.',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'table' => [
            'name' => 'Name',
            'email' => 'Email',
            'roles' => 'Roles',
            'status' => 'Status',
        ],
    ],

    'roles' => [
        'title' => 'Roles & Permissions',
        'subtitle' => 'Review role setup and inspect permission coverage.',
        'manage_all' => 'Manage Roles',
        'empty_title' => 'No roles found',
        'empty_description' => 'Create a role to define permission bundles.',
        'matrix_title' => 'Permission Matrix',
        'matrix_subtitle' => 'Snapshot of common permissions by module.',
        'preview_for' => 'Previewing permissions for :role',
        'permission_group' => 'Permission Group',
        'permissions' => 'Permissions',
        'table' => [
            'role' => 'Role',
            'permissions_count' => 'Permissions',
        ],
        'groups' => [
            'transactions' => 'Transactions',
            'products' => 'Products',
            'members' => 'Members',
            'reports' => 'Reports',
            'settings' => 'Settings',
            'users' => 'Users',
        ],
    ],

    'printer' => [
        'title' => 'Printer Settings',
        'subtitle' => 'Configure receipt printer connection and paper output.',
        'open_advanced' => 'Open Printer Tools',
        'fields' => [
            'name' => 'Printer Name',
            'driver' => 'Driver',
            'port' => 'Port',
            'ip_address' => 'IP Address',
            'paper_size' => 'Paper Size',
        ],
    ],

    'about' => [
        'title' => 'About',
        'subtitle' => 'Basic application and store information for this tenant.',
        'support_title' => 'Need help?',
        'support_description' => 'Visit the support center for setup guides and troubleshooting.',
        'fields' => [
            'application_name' => 'Application Name',
            'version' => 'Version',
            'license' => 'License',
            'store_name' => 'Store Name',
            'store_address' => 'Store Address',
        ],
    ],

    'profile' => [
        'title' => 'Profile',
        'subtitle' => 'Update your account details, locale, and security preferences.',
        'sections' => [
            'account' => 'Account',
            'contact' => 'Contact',
            'localization' => 'Localization',
            'security' => 'Security',
        ],
        'fields' => [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'language' => 'Language',
            'timezone' => 'Timezone',
            'new_password' => 'New Password',
            'confirm_password' => 'Confirm Password',
        ],
    ],
];
