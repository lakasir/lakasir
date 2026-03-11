<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-[#f2f2f2] text-[#111111] antialiased">
    <div class="min-h-screen">
        @include('components.shared.header')

        <main class="mx-auto w-full max-w-6xl px-4 pb-10 pt-2 md:px-6 md:pb-12">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
