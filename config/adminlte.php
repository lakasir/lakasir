<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'Lakasir',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '<b>Akasir</b>',
    'logo_img' => '/assets/lakasir-sm.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xl',
    'logo_img_alt' => 'Lakasir',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => false,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#661-authentication-views-classes
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#662-admin-panel-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#67-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#68-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => false,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#69-urls
    |
    */

    'use_route_url' => true,

    'dashboard_url' => 'dashboard',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => 'register',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => 'profile.index',

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#610-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#611-menu
    |
    */

    'menu' => [
        /* [ */
        /*     'text' => 'search', */
        /*     'search' => true, */
        /*     'topnav_right' => true, */
        /*     'url'  => 'transaction', */
        /* ], */
        [
            'text' => '',
            'topnav_right' => true,
            'icon' => 'fas fa-fw fa-plus',
            'submenu' => [
                [
                    'text' => 'app.purchasings.title',
                    'url'  => 'transaction/purchasing/create',
                    'can' => 'create-purchasing',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'app.items.title',
                    'url'  => 'master/item/create',
                    'can' => 'create-item',
                    'icon' => 'fas fa-fw fa-plus',
                ],
                [
                    'text' => 'app.sellings.title.name',
                    'url'  => '/transaction/cashier',
                    'can' => 'create-selling',
                    'icon' => 'fas fa-fw fa-plus',
                ],
            ],
        ],
        [
            'text' => 'transaction',
            'url'  => 'transaction',
            'icon' => 'fas fa-fw fa-file',
            'can'  => 'browse-transaction',
        ],
        [
            'text'        => 'dashboard',
            'url'         => 'dashboard',
            'icon'        => 'fas fa-fw fa-tachometer-alt',
            /* 'label'       => 'new', */
            /* 'label_color' => 'success', */
        ],
        [
            'text' => 'menu.item',
            'key' => 'item',
            'icon' => 'fas fa-fw fa-file',
            'url'  => 'master/item',
            'can' => 'browse-item',
            'active' => ['master/item', 'master/item/create', 'regex:/^master\/item\/[0-9]\/edit/']
        ],
        /* ['header' => 'menu.transaction'], */
        [
            'text'    => 'menu.transaction',
            'icon'    => 'fas fa-fw fa-dollar-sign',
            'can' => ['browse-purchasing', 'browse-selling'],
            'submenu' => [
                [
                    'text' => 'menu.purchasing',
                    'url'  => 'transaction/purchasing',
                    'can' => 'browse-purchasing',
                    'active' => ['transaction/purchasing', 'transaction/purchasing/create', 'regex:/^transaction\/purchasing\/[0-9]/']
                ],
                [
                    'text' => 'menu.bill_purchasing',
                    'url'  => 'transaction/bill_purchasing?filter[key]=is_paid&&filter[value]=0',
                    'can' => 'browse-purchasing',
                    'active' => ['regex:/^transaction\/bill_purchasing.*/']
                ],
                [
                    'text' => 'menu.cashier',
                    'url'  => 'transaction/cashier',
                    'can' => 'browse-selling',
                    'active' => ['transaction/cashier', 'transaction/cashier/create', 'regex:/^transaction\/cashier\/[0-9]/']
                ],
                [
                    'text' => 'app.sellings.title.index',
                    'url'  => '/transaction/selling',
                    'active' => ['transaction/selling', 'transaction/selling/create', 'regex:/^transaction\/selling\/[0-9]/'],
                    'can' => 'browse-selling',
                ],
            ],
        ],
        /* ['header' => 'menu.customer_data'], */
        [
            'text'    => 'menu.customer',
            'icon'    => 'fas fa-fw fa-users',
            'can' => ['browse-customer', 'browse-group', 'browse-customer_type'],
            'submenu' => [
                [
                    'text' => 'menu.customer_list',
                    'url'  => 'master/customer',
                    'can' => 'browse-customer',
                    'active' => ['master/customer', 'master/customer/create', 'regex:/^master\/customer.*/']
                ],
                [
                    'text' => 'menu.group',
                    'url'  => 'master/group',
                    'active' => ['master/group', 'master/group/create', 'regex:/^master\/group\/[0-9]\/edit/'],
                    'can' => 'browse-group'
                ],
                [
                    'text' => 'menu.customer_type',
                    'url'  => 'master/type_customer',
                    'active' => ['master/type_customer', 'master/type_customer/create', 'regex:/^master\/type_customer\/[0-9]\/edit/'],
                    'can' => 'browse-customer_type'
                ],
            ],
        ],
        /* ['header' => 'menu.master_data'], */
        [
            'text'    => 'menu.master_data',
            'icon'    => 'fas fa-fw fa-database',
            'can' => ['browse-unit', 'browse-category', 'browse-supplier'],
            'submenu' => [
                [
                    'text' => 'menu.unit',
                    'url'  => 'master/unit',
                    'active' => ['master/unit', 'master/unit/create', 'regex:/^master\/unit\/[0-9]\/edit/'],
                    'can' => 'browse-unit'
                ],
                [
                    'text' => 'menu.category',
                    'url'  => 'master/category',
                    'active' => ['master/category', 'master/category/create', 'regex:/^master\/category\/[0-9]\/edit/'],
                    'can' => 'browse-category'
                ],
                [
                    'text' => 'menu.supplier',
                    'url'  => 'master/supplier',
                    'active' => ['master/supplier', 'master/supplier/create', 'regex:/^master\/supplier.*/'],
                    'can' => 'browse-supplier'
                ],
                [
                    'text' => 'menu.payment_method',
                    'url'  => 'master/payment_method',
                    'active' => ['master/payment_method', 'master/payment_method/create', 'regex:/^master\/payment_method\/[0-9]\/edit/'],
                    'can' => 'browse-payment_method'
                ],
            ],
        ],
        /* ['header' => 'menu.user_management'], */
        [
            'text' => 'menu.user',
            'icon' => 'fas fa-fw fa-users',
            'can' => ['browse-user', 'browse-role'],
            'submenu' => [
                [
                    'text' => 'menu.user_list',
                    'url'  => 'user',
                    'active' => ['user', 'user/create', 'regex:/^user\/[0-9]\/edit/'],
                    'can' => 'browse-user'
                ],
                [
                    'text' => 'menu.role',
                    'url'  => 'user/role',
                    'active' => ['user/role', 'user/role/create', 'regex:/^user\/role\/[0-9]\/edit/'],
                    'can' => 'browse-role'
                ],
            ]
        ],
        ['header' => 'account_settings'],
        [
            'can' => 'browse-profile',
            'text' => 'menu.profile',
            'url'  => 'user/profile',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'menu.change_password',
            'url'  => 'user/change_password',
            'icon' => 'fas fa-fw fa-lock',
        ],
        ['header' => 'menu.settings', 'can' => [ 'browse-general-setting'] ],
        [
            'text' => 'menu.settings',
            'url'  => 's/general',
            'icon' => 'fas fa-fw fa-cogs',
            'active' => ['s/general', 'regex:/^s\/general\/[aA-zZ]/'],
            'can' => ['browse-general-setting']
        ],
        /* [ */
        /*     'text' => 'menu.default', */
        /*     'url'  => 's/default', */
        /*     'icon' => 'fas fa-fw fa-th', */
        /*     'can' => ['browse-general-setting'] */
        /* ], */
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#612-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        // JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#613-plugins
    |
    */

    'plugins' => [
        [
            'name' => 'Datatables',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        [
            'name' => 'Select2',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        [
            'name' => 'Chartjs',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        [
            'name' => 'Sweetalert2',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        [
            'name' => 'Pace',
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        [
            'name' => 'DatePicker',
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//unpkg.com/gijgo@1.9.13/css/gijgo.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//unpkg.com/gijgo@1.9.13/js/gijgo.min.js',
                ],
            ],
        ],
        [
            'name' => 'ionicons',
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//unpkg.com/gijgo@1.9.13/css/gijgo.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//unpkg.com/ionicons@5.0.0/dist/ionicons.js',
                ],
            ],
        ]
    ],
];
