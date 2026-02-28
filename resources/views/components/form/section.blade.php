@props([
    'title' => null,
    'description' => null,
])

<div class="space-y-4">
    @if($title)
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $title }}</h3>
            @if($description)
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
            @endif
        </div>
    @endif
    
    <div class="space-y-4">
        {{ $slot }}
    </div>
</div>