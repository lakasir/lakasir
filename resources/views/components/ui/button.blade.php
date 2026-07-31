@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'button',
])

@php
    $baseClass = "inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 active:translate-y-[2px] active:shadow-skeuo-pressed";
    
    $variants = [
        'default' => 'bg-primary text-primary-foreground shadow-skeuo hover:bg-primary/90',
        'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90 shadow-sm',
        'outline' => 'border border-input bg-background hover:bg-accent hover:text-accent-foreground shadow-sm',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80 shadow-sm',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground active:translate-y-0 active:shadow-none',
        'link' => 'text-primary underline-offset-4 hover:underline active:translate-y-0 active:shadow-none',
    ];
    
    $sizes = [
        'default' => 'h-10 px-4 py-2',
        'sm' => 'h-9 rounded-md px-3',
        'lg' => 'h-11 rounded-md px-8',
        'icon' => 'h-10 w-10',
    ];
    
    $classes = \Illuminate\Support\Arr::toCssClasses([
        $baseClass,
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['default'],
    ]);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
