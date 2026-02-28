@props([
    'class' => 'h-4 w-full',
])

<div {{ $attributes->merge(['class' => "{$class} bg-gray-200 dark:bg-gray-700 rounded animate-pulse"]) }}></div>