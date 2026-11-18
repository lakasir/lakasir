<x-filament-panels::page>
  @php($modules = $this->getModules())

  @if (empty($modules))
    <x-filament::section>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ __('No module installed yet.') }}
      </p>
    </x-filament::section>
  @else
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
      @foreach ($modules as $module)
        <x-filament::section>
          <x-slot name="heading">
            <div class="flex items-center gap-2">
              <x-filament::icon icon="heroicon-o-puzzle-piece" class="h-5 w-5" />
              {{ $module['title'] }}
            </div>
          </x-slot>

          <x-slot name="description">
            {{ $module['package'] }}
          </x-slot>

          <dl class="space-y-1 text-sm">
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500 dark:text-gray-400">{{ __('Directory') }}</dt>
              <dd class="font-mono">{{ $module['name'] }}</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500 dark:text-gray-400">{{ __('Version') }}</dt>
              <dd>{{ $module['version'] }}</dd>
            </div>
            <div class="flex justify-between gap-4">
              <dt class="text-gray-500 dark:text-gray-400">{{ __('Filament resources') }}</dt>
              <dd>
                @if ($module['has_filament'])
                  <x-filament::badge color="success">{{ __('Available') }}</x-filament::badge>
                @else
                  <x-filament::badge color="gray">{{ __('None') }}</x-filament::badge>
                @endif
              </dd>
            </div>
          </dl>
        </x-filament::section>
      @endforeach
    </div>
  @endif
</x-filament-panels::page>
