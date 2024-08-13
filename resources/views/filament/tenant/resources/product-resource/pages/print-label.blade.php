<x-filament-panels::page>
  <div class="flex gap-x-4">
    <x-filament::section class="w-full print:w-1/3 md:w-1/3 px-2">
    </x-filament::section>
    <div class="w-1/2 print:w-1/3 md:w-1/3 px-2">
      {{ $this->form }}
    </div>
  </div>
</x-filament-panels::page>
