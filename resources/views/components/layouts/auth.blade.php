<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-figma-shell antialiased">
    <main class="auth-figma-stage">
        <button
            type="button"
            class="auth-back-link"
            aria-label="{{ __('auth.back') }}"
            onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href='{{ url('/') }}'; }"
        >
            <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
        </button>

        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
