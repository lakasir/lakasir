<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ theme: localStorage.getItem('theme') || 'light' }"
      x-init="$watch('theme', val => localStorage.setItem('theme', val))"
      :class="{ 'dark': theme === 'dark' }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Lakasir POS' }}</title>
        
        <!-- Prevent FOUC (Flash of Unstyled Content) -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased min-h-screen bg-background text-foreground overflow-hidden" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
        
        <!-- Mobile Sidebar Overlay -->
        <div 
            x-show="mobileSidebarOpen" 
            x-transition.opacity 
            class="fixed inset-0 z-40 bg-black/80 lg:hidden"
            @click="mobileSidebarOpen = false"
        ></div>

        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 flex flex-col bg-card border-r border-border transition-all duration-300 ease-in-out lg:translate-x-0"
            :class="{
                'w-64': sidebarOpen,
                'w-20': !sidebarOpen,
                'translate-x-0': mobileSidebarOpen,
                '-translate-x-full': !mobileSidebarOpen
            }"
        >
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-border">
                <div class="flex items-center gap-3 overflow-hidden">
                    <img src="{{ asset('assets/logo/image.png') }}" alt="Logo" class="h-8 w-auto shrink-0">
                    <span x-show="sidebarOpen" x-transition.opacity class="font-bold text-lg whitespace-nowrap">Lakasir</span>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 flex flex-col gap-1 px-3">
                <!-- Transaksi -->
                <div x-show="sidebarOpen" class="px-3 py-2 mt-2 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                    Transaksi
                </div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
                </a>

                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('pos.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Kasir (POS)</span>
                </a>
                
                <a href="{{ route('vouchers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('vouchers.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Diskon & Voucher</span>
                </a>

                <a href="{{ route('sellings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('sellings.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10.4 12.6a2 2 0 1 1 3 3L8 21l-4 1 1-4Z"/><path d="M4 14.5V5a2 2 0 0 1 2-2h8l6 6v10a2 2 0 0 1-2 2h-1.5"/></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Riwayat Transaksi</span>
                </a>

                <!-- Katalog -->
                <div x-show="sidebarOpen" class="px-3 py-2 mt-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                    Katalog
                </div>

                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('categories.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Kategori</span>
                </a>

                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('products.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Produk</span>
                </a>

                <!-- Data Master -->
                <div x-show="sidebarOpen" class="px-3 py-2 mt-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                    Data Master
                </div>

                <a href="{{ route('members.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('members.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Pelanggan</span>
                </a>

                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('suppliers.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Pemasok</span>
                </a>

                <a href="{{ route('payment-methods.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('payment-methods.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Metode Pembayaran</span>
                </a>

                <a href="{{ route('tables.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('tables.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Meja</span>
                </a>
                
                <!-- Inventory Group -->
                <div x-show="sidebarOpen" class="px-3 py-2 mt-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                    Inventory
                </div>
                
                <a href="{{ route('purchasings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('purchasings.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 shrink-0"><path d="M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41l-7.59-7.59a2.41 2.41 0 0 0-3.41 0Z"/><path d="m5 12 7-7"/><path d="M14.5 12 12 14.5"/></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Pembelian</span>
                </a>
                
                <a href="{{ route('stock-opnames.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('stock-opnames.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 shrink-0"><path d="m2 9 3-3 3 3"/><path d="M5 6v12"/><path d="m22 15-3 3-3-3"/><path d="M19 18V6"/></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Stock Opname</span>
                </a>
                
                <!-- Finance Group -->
                <div x-show="sidebarOpen" class="px-3 py-2 mt-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                    Finance
                </div>
                
                <a href="{{ route('receivables.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('receivables.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 shrink-0"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Piutang</span>
                </a>

                <!-- Access & Users Group -->
                <div x-show="sidebarOpen" class="px-3 py-2 mt-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                    Sistem & Akses
                </div>
                
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Karyawan</span>
                </a>

                <a href="{{ route('roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->routeIs('roles.*') ? 'bg-primary/10 text-primary' : 'hover:bg-muted text-muted-foreground hover:text-foreground' }} font-medium transition-colors">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Role & Akses</span>
                </a>
            </nav>

            <!-- Sidebar Footer (Profile & Logout) -->
            <div class="p-4 border-t border-border">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 shrink-0 rounded-full bg-primary/20 flex items-center justify-center font-bold text-primary">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" x-transition.opacity class="flex flex-col flex-1 overflow-hidden">
                        <span class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'User' }}</span>
                        <span class="text-xs text-muted-foreground truncate">{{ auth()->user()->email ?? 'user@lakasir.com' }}</span>
                    </div>
                </div>
                
                <form action="{{ route('logout') }}" method="POST" class="mt-4" x-show="sidebarOpen" x-transition.opacity>
                    @csrf
                    <x-ui.button type="submit" variant="destructive" class="w-full justify-start gap-2 h-9">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out
                    </x-ui.button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div 
            class="flex flex-col h-screen transition-all duration-300 ease-in-out"
            :class="{
                'lg:ml-64': sidebarOpen,
                'lg:ml-20': !sidebarOpen
            }"
        >
            <!-- Topbar -->
            <header class="h-16 flex shrink-0 items-center gap-4 border-b border-border bg-card px-4 lg:px-6">
                <!-- Mobile Menu Toggle -->
                <button @click="mobileSidebarOpen = true" class="lg:hidden p-2 rounded-md hover:bg-accent hover:text-accent-foreground text-muted-foreground">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <!-- Desktop Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex p-2 rounded-md hover:bg-accent hover:text-accent-foreground text-muted-foreground transition-transform duration-300" :class="{ 'rotate-180': !sidebarOpen }">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="flex-1 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">{{ $header ?? '' }}</h2>
                    
                    <!-- Dark Mode Toggle -->
                    <button @click="theme = (theme === 'dark' ? 'light' : 'dark')" class="p-2 rounded-md hover:bg-accent hover:text-accent-foreground text-muted-foreground transition-colors">
                        <!-- Sun Icon (shows in dark mode) -->
                        <svg x-show="theme === 'dark'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (shows in light mode) -->
                        <svg x-show="theme !== 'dark'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-muted/20 p-4 lg:p-8">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
