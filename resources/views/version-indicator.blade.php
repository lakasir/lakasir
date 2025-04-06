@php
    $updateChecker = app(\App\Services\UpdateChecker::class);
    $currentVersion = $updateChecker->getCurrentVersion();
    $isUpdateAvailable = $updateChecker->isUpdateAvailable();
@endphp

<a href="{{ route('filament.tenant.pages.update') }}" class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mt-2 relative">
    <span class="font-mono">v{{ $currentVersion }}</span>
    @if ($isUpdateAvailable)
        <x-filament::badge class="absolute -right-4 -top-4" size="xs">
            <span class="truncate">
                <x-heroicon-o-arrow-up class="w-2 h-4" />
            </span>
        </x-filament::badge>
    @endif
</a>
