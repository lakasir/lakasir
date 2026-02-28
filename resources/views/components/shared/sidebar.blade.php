@props([
    'collapsed' => false,
])

@php
    $menuItems = [
        [
            'name' => 'Dashboard',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
            'route' => 'dashboard',
            'permission' => null,
        ],
        [
            'name' => 'POS',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
            'children' => [
                ['name' => 'Cashier', 'route' => 'pos.cashier'],
                ['name' => 'Transactions', 'route' => 'pos.transactions'],
                ['name' => 'History', 'route' => 'pos.history'],
            ],
            'permission' => 'pos_access',
        ],
        [
            'name' => 'Master',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
            'children' => [
                ['name' => 'Products', 'route' => 'products.index'],
                ['name' => 'Categories', 'route' => 'categories.index'],
                ['name' => 'Members', 'route' => 'members.index'],
                ['name' => 'Suppliers', 'route' => 'suppliers.index'],
                ['name' => 'Payment Methods', 'route' => 'payment-methods.index'],
            ],
            'permission' => 'master_access',
        ],
        [
            'name' => 'Inventory',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
            'children' => [
                ['name' => 'Stock Opname', 'route' => 'stock-opnames.index'],
                ['name' => 'Purchasing', 'route' => 'purchasings.index'],
            ],
            'permission' => 'inventory_access',
        ],
        [
            'name' => 'Finance',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'children' => [
                ['name' => 'Receivables', 'route' => 'receivables.index'],
                ['name' => 'Vouchers', 'route' => 'vouchers.index'],
            ],
            'permission' => 'finance_access',
        ],
        [
            'name' => 'Reports',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
            'children' => [
                ['name' => 'Sales', 'route' => 'reports.sales'],
                ['name' => 'Products', 'route' => 'reports.products'],
                ['name' => 'Cashiers', 'route' => 'reports.cashiers'],
                ['name' => 'Purchases', 'route' => 'reports.purchases'],
            ],
            'permission' => 'reports_access',
        ],
        [
            'name' => 'Settings',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            'children' => [
                ['name' => 'General', 'route' => 'settings.general'],
                ['name' => 'Users', 'route' => 'settings.users'],
                ['name' => 'Roles', 'route' => 'settings.roles'],
                ['name' => 'Printer', 'route' => 'settings.printer'],
            ],
            'permission' => 'settings_access',
        ],
    ];
@endphp

<aside 
    x-data="{ 
        sidebarOpen: true,
        mobileOpen: false,
        expandedMenus: {}
    }"
    class="hidden lg:flex lg:flex-col lg:w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700"
>
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
        <a href="/" class="flex items-center gap-2">
            <img src="{{ asset('assets/logo/image.png') }}" alt="Lakasir" class="h-8 w-auto">
            <span class="text-xl font-bold text-lakasir-primary">Lakasir</span>
        </a>
    </div>
    
    <nav class="flex-1 overflow-y-auto py-4 px-3">
        <ul class="space-y-1">
            @foreach($menuItems as $item)
                <li>
                    @if(isset($item['children']))
                        <button 
                            @click="expandedMenus['{{ $item['name'] }}'] = !expandedMenus['{{ $item['name'] }}']"
                            class="w-full flex items-center justify-between px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        >
                            <div class="flex items-center gap-3">
                                {!! $item['icon'] !!}
                                <span class="font-medium">{{ $item['name'] }}</span>
                            </div>
                            <svg 
                                class="w-4 h-4 transition-transform" 
                                :class="{ 'rotate-180': expandedMenus['{{ $item['name'] }}'] }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <ul 
                            x-show="expandedMenus['{{ $item['name'] }}']"
                            x-collapse
                            class="ml-8 mt-1 space-y-1"
                        >
                            @foreach($item['children'] as $child)
                                <li>
                                    <a 
                                        href="{{ Route::has($child['route']) ? route($child['route']) : '#' }}" 
                                        class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200 transition-colors"
                                    >
                                        {{ $child['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a 
                            href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                            class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        >
                            {!! $item['icon'] !!}
                            <span class="font-medium">{{ $item['name'] }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
    
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <x-ui.avatar :name="auth()->user()->name ?? 'User'" size="sm" />
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                    {{ auth()->user()->name ?? 'User' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ auth()->user()->email ?? '' }}
                </p>
            </div>
        </div>
    </div>
</aside>

<div 
    x-data="{ mobileOpen: false }"
    class="lg:hidden fixed inset-0 z-40"
    x-show="mobileOpen"
    x-cloak
>
    <div 
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50"
        @click="mobileOpen = false"
    ></div>
    
    <aside 
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-xl"
    >
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('assets/logo/image.png') }}" alt="Lakasir" class="h-8 w-auto">
                <span class="text-xl font-bold text-lakasir-primary">Lakasir</span>
            </a>
            <button @click="mobileOpen = false" class="text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-4 px-3">
            <ul class="space-y-1">
                @foreach($menuItems as $item)
                    <li>
                        @if(isset($item['children']))
                            <button 
                                @click="expandedMenus['{{ $item['name'] }}'] = !expandedMenus['{{ $item['name'] }}']"
                                class="w-full flex items-center justify-between px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            >
                                <div class="flex items-center gap-3">
                                    {!! $item['icon'] !!}
                                    <span class="font-medium">{{ $item['name'] }}</span>
                                </div>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <ul class="ml-8 mt-1 space-y-1">
                                @foreach($item['children'] as $child)
                                    <li>
                                        <a 
                                            href="{{ Route::has($child['route']) ? route($child['route']) : '#' }}" 
                                            class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                        >
                                            {{ $child['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a 
                                href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                                class="flex items-center gap-3 px-3 py-2 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                {!! $item['icon'] !!}
                                <span class="font-medium">{{ $item['name'] }}</span>
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
    </aside>
</div>