<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

</head>

<body class="min-h-screen bg-[color:var(--ui-surface-2)] font-sans text-[color:var(--ui-text)] antialiased">
  <header class="border-b border-[color:var(--ui-rule)] bg-[color:var(--ui-surface)]">
    <div class="mx-auto flex h-14 max-w-5xl items-center px-6">
      <a href="/" class="flex items-center gap-x-2 text-lg font-semibold">
        <img src="{{ asset('assets/logo/image.png') }}" alt="" class="h-8 w-8 object-contain">
        <span>{{ config('app.name', 'Lakasir') }}</span>
      </a>
    </div>
  </header>

  <main class="ui-blueprint flex min-h-[calc(100vh-3.5rem)] items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
      @if (session()->has('message'))
        <div class="mb-4 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
          {{ session('message') }}
        </div>
      @endif

      <div class="ui-card bg-[color:var(--ui-surface)] p-6 shadow-[var(--ui-shadow)] ring-1 ring-[color:var(--ui-rule)]">
        {{ $slot }}
      </div>
    </div>
  </main>

  @livewireScripts
</body>

</html>
