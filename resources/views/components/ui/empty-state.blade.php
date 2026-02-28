@props([
    'title' => null,
    'description' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    @if($icon)
        <div class="flex justify-center mb-4">
            <div class="text-gray-400 dark:text-gray-500">
                {!! $icon !!}
            </div>
        </div>
    @endif
    
    @if($title)
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $title }}</h3>
    @endif
    
    @if($description)
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 max-w-sm mx-auto">{{ $description }}</p>
    @endif
    
    @if(isset($action))
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
    
    {{ $slot }}
</div>