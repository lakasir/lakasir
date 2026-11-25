<div>
  @if ($companies->count() > 1)
    <x-filament::dropdown placement="bottom-start" teleport>
      <x-slot name="trigger">
        <x-filament::button color="gray" size="sm" icon="heroicon-o-building-storefront">
          {{ $current?->name ?? __('Select shop') }}
          <x-filament::icon icon="heroicon-m-chevron-down" class="h-4 w-4" />
        </x-filament::button>
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
