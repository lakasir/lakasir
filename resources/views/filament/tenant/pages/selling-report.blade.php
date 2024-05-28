<x-filament-panels::page>
  <form wire:submit="generate">
    {{ $this->form }}
    <br>
    <x-filament::button type="submit">
      {{ __('Generate') }}
    </x-filament::button>
  </form>
</x-filament-panels::page>
