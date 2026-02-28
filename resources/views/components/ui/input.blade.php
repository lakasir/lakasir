@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'hint' => null,
    'icon' => null,
    'disabled' => false,
])

@php
    $inputId = $attributes->get('id', 'input-' . Str::random(8));
    $hasError = !empty($error);
@endphp

<div class="w-full" wire:ignore.self>
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-400">{!! $icon !!}</span>
            </div>
        @endif
        
        <input 
            type="{{ $type }}"
            id="{{ $inputId }}"
            {{ $attributes->merge([
                'class' => implode(' ', array_filter([
                    'w-full rounded-lg border bg-white dark:bg-gray-900 px-3 py-2 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500',
                    'focus:outline-none focus:ring-2 focus:ring-lakasir-primary focus:border-transparent',
                    'disabled:bg-gray-100 disabled:dark:bg-gray-800 disabled:cursor-not-allowed disabled:text-gray-500',
                    $icon ? 'pl-10' : '',
                    $hasError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600',
                ]))
            ]) }}
        >
    </div>
    
    @if($hint && !$hasError)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1 text-sm text-red-500">{{ $error }}</p>
    @endif
</div>