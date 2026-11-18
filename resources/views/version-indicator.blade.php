@php
  $updateChecker = app(\App\Services\UpdateChecker::class);
  $currentVersion = $updateChecker->getCurrentVersion();
  $isUpdateAvailable = $updateChecker->isUpdateAvailable();
@endphp

<a href="{{ route('filament.tenant.pages.update') }}"
  class="relative mt-2 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
  @if ($currentVersion === 'Development')
    <span class="font-mono">{{ $currentVersion }}</span>
  @else
    <span class="font-mono">v{{ $currentVersion }}</span>
  @endif
  @if ($isUpdateAvailable)
    <x-filament::badge class="absolute -right-4 -top-4" size="xs">
      <span class="truncate">
        <x-heroicon-o-arrow-up class="h-4 w-2" />
      </span>
    </x-filament::badge>
  @endif
</a>
