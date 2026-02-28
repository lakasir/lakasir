@props([
    'position' => 'bottom-start',
    'width' => 'md',
])

@php
    $positionClasses = match($position) {
        'bottom-start' => 'left-0 origin-top-left',
        'bottom-end' => 'right-0 origin-top-right',
        'top-start' => 'left-0 bottom-full mb-2 origin-bottom-left',
        'top-end' => 'right-0 bottom-full mb-2 origin-bottom-right',
        default => 'left-0 origin-top-left',
    };
    
    $widthClasses = match($width) {
        'sm' => 'w-40',
        'md' => 'w-56',
        'lg' => 'w-72',
        default => 'w-56',
    };
@endphp

<div 
    x-data="{ open: false }"
    @click.away="open = false"
    @keydown.escape.window="open = false"
    class="relative inline-block"
>
    <div @click="open = !open">
        {{ $trigger }}
    </div>
    
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
        class="absolute {{ $positionClasses }} {{ $widthClasses }} mt-2 rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black/5 dark:ring-white/10 focus:outline-none z-50"
    >
        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</div>