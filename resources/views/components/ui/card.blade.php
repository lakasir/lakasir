@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'md',
    'shadow' => true,
])

@php
    $paddingClasses = match($padding) {
        'none' => '',
        'sm' => 'p-3',
        'md' => 'p-4 md:p-6',
        'lg' => 'p-6 md:p-8',
        default => 'p-4 md:p-6',
    };
    
    $shadowClass = $shadow ? 'shadow-md' : '';
@endphp

<div {{ $attributes->merge(['class' => "bg-white dark:bg-gray-800 rounded-xl {$shadowClass} overflow-hidden"]) }}>
    @if($title || isset($header))
        <div class="border-b border-gray-200 dark:border-gray-700 px-4 md:px-6 py-4">
            @if(isset($header))
                {{ $header }}
            @else
                <div>
                    @if($title)
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif
        </div>
    @endif
    
    <div class="{{ $paddingClasses }}">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 md:px-6 py-4 bg-gray-50 dark:bg-gray-900">
            {{ $footer }}
        </div>
    @endif
</div>