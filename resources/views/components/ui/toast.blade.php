@props([
    'type' => 'success',
    'timeout' => 3000,
])

<div 
    x-data="{
        show: true,
        type: '{{ $type }}',
        timeout: {{ $timeout }},
        init() {
            if (this.timeout > 0) {
                setTimeout(() => this.show = false, this.timeout)
            }
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    x-cloak
    {{ $attributes->merge(['class' => 'flex items-center gap-3 p-4 rounded-lg shadow-lg max-w-sm w-full']) }}
>
    @php
        $bgClasses = match($type) {
            'success' => 'bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800',
            'error' => 'bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800',
            'warning' => 'bg-yellow-50 dark:bg-yellow-900/50 border border-yellow-200 dark:border-yellow-800',
            'info' => 'bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-800',
            default => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700',
        };
        
        $iconColor = match($type) {
            'success' => 'text-green-500',
            'error' => 'text-red-500',
            'warning' => 'text-yellow-500',
            'info' => 'text-blue-500',
            default => 'text-gray-500',
        };
    @endphp
    
    <div class="flex-shrink-0 {{ $iconColor }}">
        @if($type === 'success')
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        @elseif($type === 'error')
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
        @elseif($type === 'warning')
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        @elseif($type === 'info')
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        @endif
    </div>
    
    <div class="flex-1 text-sm text-gray-700 dark:text-gray-200">
        {{ $slot }}
    </div>
    
    <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>