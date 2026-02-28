@props([
    'name' => 'modal',
    'title' => null,
    'size' => 'md',
    'position' => 'center',
])

@php
    $sizeClasses = match($size) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        'full' => 'max-w-full mx-4',
        default => 'max-w-md',
    };
@endphp

<div 
    x-data="{ 
        open: @entangle($attributes->wire('model')),
        close() { this.open = false },
        closeOnBackdrop(e) { if (e.target === e.currentTarget) this.close() },
        closeOnEscape(e) { if (e.key === 'Escape') this.close() }
    }"
    x-init="document.addEventListener('keydown', closeOnEscape)"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    @click="closeOnBackdrop"
>
    <div class="min-h-screen px-4 flex items-center justify-center">
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50"
        ></div>
        
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="relative w-full {{ $sizeClasses }} bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden"
        >
            @if($title)
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
                    <button @click="close" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            
            <div class="p-6">
                {{ $slot }}
            </div>
            
            @if(isset($actions))
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-end gap-3">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</div>