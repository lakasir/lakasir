@props([
    'src' => null,
    'name' => null,
    'size' => 'md',
])

@php
    $sizeClasses = match($size) {
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-12 w-12 text-base',
        'xl' => 'h-16 w-16 text-lg',
        default => 'h-10 w-10 text-sm',
    };
    
    $initials = '';
    if ($name && !$src) {
        $words = explode(' ', $name);
        $initials = count($words) >= 2 
            ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
            : strtoupper(substr($name, 0, 2));
    }
@endphp

<div {{ $attributes->merge(['class' => "{$sizeClasses} rounded-full bg-lakasir-primary flex items-center justify-center text-white font-medium overflow-hidden flex-shrink-0"]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $name ?? 'Avatar' }}" class="h-full w-full object-cover">
    @else
        <span>{{ $initials }}</span>
    @endif
</div>