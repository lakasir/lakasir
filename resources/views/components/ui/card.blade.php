<div {{ $attributes->merge(['class' => 'rounded-lg border border-border bg-card text-card-foreground shadow-skeuo-card']) }}>
    @if(isset($header))
        <div class="flex flex-col space-y-1.5 p-6">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6 pt-0">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="flex items-center p-6 pt-0">
            {{ $footer }}
        </div>
    @endif
</div>
