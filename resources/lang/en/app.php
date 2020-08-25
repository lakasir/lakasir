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
    'profiles' => [
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
        'activity' => 'Activity'
    ],
    'auth' => [
        'placeholder' => [
            'email' => 'Type your email',
            'password' => 'Type your passwords'
        ],
        'label' => [
            'remember' => 'Remeber Me'
        ],
        'login' => 'Login',
        'forgot_password' => 'Forgot Password'
    ],
    'items' => [
        'title' => 'Item Data',
        'placeholder' => [
            'name' => 'Name',
            'images' => 'Images',
            'internal_production' => 'Internal Production',
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
                'stock' => 'Stock'
            ]
        ],
        'column' => [
            'name' => 'Name',
            'images' => 'Images',
            'internal_production' => 'Internal Production',
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
                'empty' => 'Empty Stock'
            ]
        ],
        'edit' => [
            'title' => 'Edit Items'
        ],
        'create' => [
            'title' => 'Create Items'
        ],
    ],
    'selling' => [
        'title' => [
            'cashier' => 'Cashier',
            'index' => 'Index'
        ],
        'placeholder' => [
            'search_item' => 'Search Item'
        ]
    ],
    'purchasings' => [
        'title' => 'Purchasing',
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
            'items' => [
                'header' => 'Item',
                'name' => 'Item Name',
                'qty' => 'Qty',
                'price' => 'Price',
                'initial_price' => 'Initial Price',
                'selling_price' => 'Selling Price',
                'total' => 'Total'
            ]
        ],
        'placeholder' => [
            'date' => 'Date',
            'supplier' => 'Supplier',
            'payment_method' => 'Payment Method',
            'invoice_number' => 'Invoice Number',
            'total_initial_price' => 'Total Initial Price',
            'total_selling_price' => 'Total Selling Price',
            'total_qty' => 'Total Qty',
            'note' => 'Note',
            'paid' => 'Paid'
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
    ],
    'customers' => [
        'title' => 'Title',
        'column' => [
            'name' => 'Name',
            'email' => 'Email',
            'code' => 'Code',
            'total_point' => 'Total Point'
        ],
        'placeholder' => [
            'name' => 'Name',
            'email' => 'Email',
            'code' => 'Code',
            'total_point' => 'Total Point'
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
        'title' => 'Unit',
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
            'phone' => 'Phone',
            'address' => 'Address'
        ],
        'placeholder' => [
            'name' => 'Name',
            'shop_name' => 'Shop Name',
            'code' => 'Code',
            'phone' => 'Phone',
            'address' => 'Address'
        ],
        'edit' => [
            'title' => 'Edit Supplier'
        ],
        'create' => [
            'title' => 'Create Supplier'
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
        ]
    ],
    'role' => [
        'title' => 'Role',
        'column' => [
            'name' => 'Role Name',
            'guard_name' => 'Guard Name',
            'permission_name' => 'Permission Name',
            'permission' => 'Permission'
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
        ]
    ],
    'global' => [
        'submit' => 'Submit',
        'action' => 'Action',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'create' => 'Create',
        'created_at' => 'Created At',
        'suredelete' => 'Are You Sure?',
        'error_old_password' => 'Your Old Password is Not Valid',
        'cancel' => 'Cancel',
        'total' => 'Total',
        'message' => [
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
            'success' => 'Success',
        ]
    ]
];
