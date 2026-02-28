@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
    $variantClasses = match($variant) {
        'primary' => 'bg-lakasir-primary text-white',
        'secondary' => 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100',
        'success' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
        'danger' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
        'warning' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
        default => 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200',
    };

    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-sm',
        default => 'px-2.5 py-0.5 text-sm',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-medium rounded-full {$variantClasses} {$sizeClasses}"]) }}>
    {{ $slot }}
</span>