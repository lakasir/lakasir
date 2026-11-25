<div>
  @if ($companies->count() > 1)
    <x-filament::dropdown placement="bottom-start" teleport>
      <x-slot name="trigger">
        <button
          type="button"
          class="fi-btn fi-btn-size-sm inline-flex items-center gap-1.5 rounded-lg bg-gray-50 px-3 py-1.5 text-sm font-medium text-gray-950 ring-1 ring-gray-950/10 transition hover:bg-gray-100 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:hover:bg-white/10"
        >
          <x-filament::icon icon="heroicon-m-building-storefront" class="h-4 w-4" />
          <span class="max-w-[12rem] truncate">{{ $current?->name ?? __('Select shop') }}</span>
          <x-filament::icon icon="heroicon-m-chevron-down" class="h-4 w-4" />
        </button>
      </x-slot>

      <x-filament::dropdown.list>
        @foreach ($companies as $company)
          <x-filament::dropdown.list.item
            :icon="$company->isCurrent() ? 'heroicon-m-check' : null"
            :color="$company->isCurrent() ? 'primary' : 'gray'"
            wire:click="switchTo({{ $company->id }})"
          >
            {{ $company->name }}
          </x-filament::dropdown.list.item>
        @endforeach
      </x-filament::dropdown.list>
    </x-filament::dropdown>
  @endif
</div>
