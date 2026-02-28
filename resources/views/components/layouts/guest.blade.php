<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lakasir - Aplikasi Point of Sale (POS) Gratis">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased min-h-screen flex flex-col">
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('assets/logo/image.png') }}" alt="Lakasir" class="h-8 w-auto">
                <span class="text-xl font-bold text-lakasir-primary">Lakasir</span>
            </a>
        </div>
    </header>
    
    <main class="flex-1">
        {{ $slot }}
    </main>
    
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    &copy; {{ date('Y') }} Lakasir. All rights reserved.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-lakasir-primary">Privacy</a>
                    <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-lakasir-primary">Terms</a>
                    <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-lakasir-primary">Contact</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>