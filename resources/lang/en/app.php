<?php

return [
    'completed' => [
        'message' => 'Congratulations, now you can use Lakasir for manage your selling',
        'link' => 'Go Login Now!!!'
    ],
    'install' => [
        'database' => 'Register Your Database',
        'next' => 'Next',
        'submit' => 'Submit',
        'tab' => [
            'user' => 'User',
            'company' => 'Company',
            'database' => 'Database'
        ],
        'placeholder' => [
            'database' => [
                'name' => 'Type Your Database Name',
                'username' => 'Type Your Database Username',
                'password' => 'Type Your Database Password',
            ],
            'user' => [
                'email' => 'Type Your Acount Email',
                'username' => 'Type Your Acount Username',
                'password' => 'Type Your Acount Password',
                'password_confirmation' => 'Confirm Your Account Password'
            ],
            'company' => [
                'business_type' => 'Choose Your Business Type',
                'business_description' => 'Type Your Business Description'
            ]
        ],
    ],
    'payment_methods' => [
        'title' => 'Payment Method',
        'column' => [
            'name' => 'Name',
            'code' => 'Code',
            'visible_in' => 'Visible In',
            'can_delete' => 'Can Delete'
        ],
        'placeholder' => [
            'name' => 'Name',
            'code' => 'Code',
            'visible_in' => 'Visible In',
            'can_delete' => 'Can Delete'
        ],
        'info' => [
            'visible_in' => [
                'empty' => 'Empty'
            ]
        ],
        'create' => [
            'title' => 'Create Payment Method'
        ],
        'edit' => [
            'title' => 'Edit Payment Method'
        ]
    ],
    'profiles' => [
        'index' => [
            'title' => 'Your Profile'
        ],
        'change_password' => [
            'title' => 'Update Password'
        ],
        'column' => [
            'bio' => 'Bio',
            'phone' => 'Phone',
            'address' => 'Address',
            'photo_profile' => 'Photo Profile',
            'lang' => 'Language'
        ],
        'placeholder' => [
            'bio' => 'Bio',
            'phone' => 'Phone',
            'address' => 'Address',
            'photo_profile' => 'Photo Profile',
            'lang' => 'Language'
        ],
        'about_me' => 'About Me',
        'settings' => 'Settings',
        'timeline' => 'Timeline',
        'activity' => 'Activity',
        'company' => 'Company'
    ],
    'settings' => [
        'general' => [
            'title' => 'General',
            'company' => [
                'title' => 'Company',
                'description' => 'Update company name, email, address, NPWP and etc.'
            ],
            'date' => [
                'title' => 'Date',
                'description' => 'Configure your date format.'
            ],
            'currency' => [
                'title' => 'Currency',
                'description' => 'Configure your currency use.'
            ],
            'appearance' => [
                'title' => 'Appearance',
                'description' => 'Configure your system appearance.'
            ],
            'plugins' => [
                'title' => 'Plugins',
                'description' => 'Add the awesome tools for Lakasir.'
            ],
        ],
    ],
    'auth' => [
        'placeholder' => [
            'email' => 'Type your email',
            'identity' => 'Type your email or username',
            'password' => 'Type your passwords'
        ],
        'label' => [
            'remember' => 'Remeber Me',
            'identity' => 'Email or Username',
            'password' => 'Passwords'
        ],
        'login' => 'Login',
        'forgot_password' => 'Forgot Password',
        'unauthorized' => 'This action is unauthorized.'
    ],
    'items' => [
        'title' => 'Item Data',
        'title_dashboard' => 'Product',
        'placeholder' => [
            'name' => 'Name',
            'images' => 'Images',
            'sku' => 'sku',
            'internal_production' => 'Internal Production',
            'category' => [
                'name' => 'Choose one of category',
            ],
            'unit' => [
                'name' => 'Choose one of unit'
            ],
            'price' => [
                'selling_price' => 'Selling Price',
                'initial_price' => 'Initial Price'
            ],
            'stock' => [
                'amount' => 'Amount',
                'stock' => 'Stock',
                'last_stock' => 'Last Stock'
            ]
        ],
        'column' => [
            'name' => 'Name',
            'images' => 'Images',
            'internal_production' => 'Internal Production',
            'sales' => 'Sales',
            'sku' => 'Sku',
            'category' => [
                'name' => 'Category Name',
            ],
            'unit' => [
                'name' => 'Unit Name'
            ],
            'price' => [
                'selling_price' => 'Selling Price',
                'initial_price' => 'Initial Price'
            ],
            'stock' => [
                'amount' => 'Amount',
                'stock' => 'Stock',
                'empty' => 'Empty',
                'last_stock' => 'Last Stock'
            ],
            'item_type' => [
                'name' => 'Item Type',
                'label' => [
                    'default' => 'Default',
                    'imei' => 'Imei',
                    'variant' => 'Variant',
                    'multi_unit' => 'Multi Unit',
                    'package' => 'Package',
                    'raw_material' => 'Raw Material',
                ],
                'value' => [
                    'default' => '0',
                    'imei' => '1',
                    'variant' => '2',
                    'multi_unit' => '3',
                    'package' => '4',
                    'raw_material' => '5',
                ]
            ]
        ],
        'export' => [
            'name' => 'Name',
            'images' => 'Images',
            'internal_production' => 'Internal Production (Yes / No)',
            'category' => [
                'name' => 'Category Name ( Leave empty for use Umum )',
            ],
            'unit' => [
                'name' => 'Unit Name'
            ],
            'price' => [
                'selling_price' => 'Selling Price',
                'initial_price' => 'Initial Price'
            ],
            'stock' => [
                'amount' => 'Amount',
                'last_stock' => 'Last Stock ( If stock is empty do not need to be filled )'
            ],
        ],
        'edit' => [
            'title' => 'Edit Items',
            'prices_stock' => 'Edit Rate Or Stocks',
        ],
        'create' => [
            'title' => 'Create Items',
            'category' => 'Create Category'
        ],
    ],
    'sellings' => [
        'title' => [
            'cashier' => 'Cashier',
            'index' => 'List Selling',
            'name' => 'Selling',
            'submit' => 'Submit Order',
            'detail' => 'Selling Detail'
        ],
        'placeholder' => [
            'search_item' => 'Search Item'
        ],
        'column' => [
            'payment_method' => 'Payment Method',
            'transaction_number' => 'Transaction Number',
            'date' => 'Date',
            'user' => 'User',
            'customer' => 'Customer',
            'money' => 'Money',
            'total_price' => 'Total Price',
            'total_qty' => 'Total Qty',
            'total_profit' => 'Total Profit',
            'refund' => 'Refund',
            'detail' => [
                'item_name' => 'Item Name',
                'qty' => 'Qty',
                'price' => 'Price',
                'profit' => 'Profit'
            ]
        ],
        'menu' => [
            'activity' => 'Activity',
            'sell' => 'Sell',
            'profile' => 'Profile'
        ],
        'validation' => [
            'less_price' => 'The Money entered is less than :money'
        ],
        'total_price' => 'Total Price',
        'carts' => 'Cart',
        'submit_order' => 'Submit Order'
    ],
    'purchasings' => [
        'title' => 'Purchasing',
        'create_title' => 'Create Purchasing',
        'column' => [
            'date' => 'Date',
            'supplier' => 'Supplier',
            'payment_method' => 'Payment Method',
            'invoice_number' => 'Invoice Number',
            'total_initial_price' => 'Total Initial Price',
            'total_selling_price' => 'Total Selling Price',
            'total_qty' => 'Total Qty',
            'note' => 'Note',
            'paid' => 'Paid',
            'user' => 'User',
            'items' => [
                'header' => 'Item',
                'name' => 'Item Name',
                'qty' => 'Qty',
                'price' => 'Price',
                'initial_price' => 'Initial Price',
                'selling_price' => 'Selling Price',
                'total' => 'Total'
            ],
            'validation' => [
                'item_doesnot_have_price' => 'the :item is didnot has price, you must assign the price'

            ]
        ],
        'placeholder' => [
            'date' => 'Date',
            'supplier' => 'Choose one of Payment Supplier',
            'payment_method' => 'Choose one of Payment Method',
            'invoice_number' => 'Invoice Number',
            'total_initial_price' => 'Total Initial Price',
            'total_selling_price' => 'Total Selling Price',
            'total_qty' => 'Total Qty',
            'note' => 'Note',
            'paid' => 'Paid',
            'user' => 'User',
            'items' => [
                'header' => 'Item',
                'name' => 'Choose one of items',
                'qty' => 'Qty',
                'price' => 'Price',
                'initial_price' => 'Initial Price',
                'selling_price' => 'Selling Price',
                'total' => 'Total'
            ]
        ],
        'paid' => [
            'true' => 'Already Paid Off',
            'false' => 'Not Paid Yet'
        ],
        'note' => [
            'nothing_note' => 'No Note'
        ],
        'info' => [
            'invoice_number' => 'Leave it Empty for use default generate invoice number',
            'date' => 'Leave it Empty for use today Date'
        ],
        'edit' => [
            'title' => 'Edit Purchasing'
        ],
        'create' => [
            'title' => 'Create Purchasing'
        ],
        'question_paid' => 'Are you yet paid?'
    ],
    'customer_types' => [
        'title' => 'Customer Type',
        'column' => [
            'customer_types_name' => 'Name',
            'name' => 'Name',
            'default_point' => 'Default Point'
        ],
        'placeholder' => [
            'customer_types_name' => 'Customer Type Name',
            'name' => 'Name',
            'default_point' => 'Default Point'
        ],
        'edit' => [
            'title' => 'Edit :title Customer Type'
        ],
        'create' => [
            'title' => 'Create Customer Type'
        ],
    ],
    'customers' => [
        'title' => 'Customer',
        'default' => [
            'customer_type' => '-'
        ],
        'column' => [
            'name' => 'Name',
            'email' => 'Email',
            'code' => 'Code',
            'total_point' => 'Total Point',
            'customer_type' => 'Customer Type',
        ],
        'placeholder' => [
            'name' => 'Name',
            'email' => 'Email',
            'code' => 'Code',
            'total_point' => 'Total Point',
            'customer_type' => 'Input Customer Type',
        ],
        'info' => [
            'code' => 'Leave it Empty for use default generate code'
        ],
        'edit' => [
            'title' => 'Edit Customer'
        ],
        'create' => [
            'title' => 'Create Customer'
        ],
    ],
    'groups' => [
        'title' => 'Group',
        'column' => [
            'name' => 'Name',
            'total_user' => 'Total User',
            'customer' => 'Customer',
            'total_customer' => 'Total Customer'
        ],
        'placeholder' => [
            'name' => 'Name',
            'customer' => 'Select Customer'
        ],
        'edit' => [
            'title' => 'Edit Group'
        ],
        'create' => [
            'title' => 'Create Group'
        ],
    ],
    'units' => [
        'title' => 'Unit',
        'column' => [
            'name' => 'Name',
        ],
        'placeholder' => [
            'name' => 'Name',
        ],
        'edit' => [
            'title' => 'Edit Unit'
        ],
        'create' => [
            'title' => 'Create Unit'
        ],
    ],
    'categories' => [
        'title' => 'Categories',
        'column' => [
            'name' => 'Name',
        ],
        'placeholder' => [
            'name' => 'Name',
        ],
        'edit' => [
            'title' => 'Edit Categories'
        ],
        'create' => [
            'title' => 'Create Categories'
        ],
    ],
    'suppliers' => [
        'title' => 'Supplier',
        'column' => [
            'name' => 'Name',
            'shop_name' => 'Shop Name',
            'code' => 'Code',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address'
        ],
        'placeholder' => [
            'name' => 'Name',
            'shop_name' => 'Shop Name',
            'code' => 'Code',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address'
        ],
        'info' => [
            'code' => 'Leave it Empty for use default generate code'
        ],
        'edit' => [
            'title' => 'Edit Supplier'
        ],
        'create' => [
            'title' => 'Create Supplier'
        ],
        'export' => [
            'name' => 'Supplier Name ( required )',
            'code' => 'Code ( Empty for default code )',
            'shop_name' => 'Shop Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address'
        ],
    ],
    'user' => [
        'title' => 'User',
        'column' => [
            'username' => 'Username',
            'email' => 'Email',
            'role' => 'Role',
            'password' => 'Password',
            'password_confirmation' => 'Password Confirmation'
        ],
        'placeholder' => [
            'username' => 'Type Your Username',
            'email' => 'Type Your Email',
            'role' => 'Type Your Role',
            'password' => 'Type Your Password',
            'password_confirmation' => 'Type Your Password Confirmation'
        ],
        'change_password' => [
            'update' => 'Change Password',
            'column' => [
                'old_password' => 'Old Password',
                'new_password' => 'New Password',
                'new_password_confirmation' => 'New Password Confirmation',
            ],
            'placeholder' => [
                'old_password' => 'Type Your Old Password',
                'new_password' => 'Type Your New Password',
                'new_password_confirmation' => 'Confirm Your New Password'
            ]
        ],
        'create' => [
            'title' => 'Create User',
        ],
        'edit' => [
            'title' => 'Edit User',
        ],
        'message' => [
            'error' => [
                'delete_user' => [
                    'owner' => 'You cant to delete owner User',
                    'has_purchasing' => 'You cant to delete user has purchasing'
                ]
            ]
        ],
        'custom_action' => [
            'assign_role' => 'Assign role'
        ]
    ],
    'role' => [
        'title' => 'Role',
        'column' => [
            'name' => 'Role Name',
            'guard_name' => 'Guard Name',
            'permission_name' => 'Permission Name',
            'permission' => 'Permission',
            'users_count' => 'Users'
        ],
        'placeholder' => [
            'name' => 'Type Your Role Name',
            'guard_name' => 'Type Your Guard Name',
            'permission_name' => 'Permission Name',
            'permission' => 'Permission'
        ],
        'create' => [
            'title' => 'Create Role',
        ],
        'edit' => [
            'title' => 'Edit Role',
        ],
        'message' => [
            'error' => [
                'delete_owner' => 'You cant delete owner'
            ]
        ]
    ],
    'companies' => [
        'title' => 'Company',
        'column' => [
            'name' => 'Name',
            'description' => 'Description',
            'business_type' => 'Business Type',
            'address' => 'Address',
            'default_currency' => 'Default Currency',
            'expected_employee' => 'Expected Employee',
            'reg_number' => 'Registration Number'
        ],
        'placeholder' => [
            'name' => 'Input your Company Name',
            'description' => 'Input your Business Description',
            'business_type' => 'Business Type',
            'address' => 'Input your Company Address',
            'default_currency' => 'Default Currency',
            'expected_employee' => 'Expected Employee',
            'reg_number' => 'Registration Number'
        ],
        'info' => [
            'reg_number' => 'Leave it Empty for use default generate registration Number'
        ]
    ],
    'global' => [
        'reload' => 'Reload',
        'submit' => 'Submit',
        'options' => 'Options',
        'action' => 'Action',
        'bulk-action' => 'Bulk Action',
        'bulk-delete' => 'Bulk Delete',
        'edit' => 'Edit',
        'view' => 'View',
        'create' => 'Create :title',
        'delete' => 'Delete',
        'create' => 'Create',
        'created_at' => 'Created At',
        'error_old_password' => 'Your Old Password is Not Valid',
        'cancel' => 'Cancel',
        'total' => 'Total',
        'download' => 'Download :title',
        'import' => 'Import :title',
        'message' => [
            'success' => [
                'create' => 'Success created the :item',
                'update' => 'Success update the :item',
                'bulk-delete' => 'Success deleted :count :item'
            ],
            'error' => [
                'create' => 'Error to create :item'
            ],
        ],
        'success' => 'Yay!!, ',
        'error' => 'Ohh no way..',
        'yes' => 'Yes',
        'no' => 'No',
        'login_cashier' => 'Login as Cashier',
        'payit' => 'Pay It',
        'more_info' => 'More Info',
        'checkAll' => 'Check All',
        'warning' => [
            'checked_first' => 'Sorry, there is no data you selected!'
        ],
        'confirm' => [
            'suredelete' => 'Are You Sure?',
            'bulk-delete' => 'Are you sure you want to mass delete?'
        ]
    ],
    'dashboard' => [
        'total_profit' => 'Total Profit',
        'total_income' => 'Total Income',
        'total_spending' => 'Total Spending',
        'new_orders' => 'New Orders',
        'sales_overview' => 'Sales Overview',
        'since_last_month' => 'Since Last Month',
        'this_year' => 'This Year',
        'last_year' => 'Last Year'
    ],
];
