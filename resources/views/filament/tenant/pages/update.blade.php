<x-filament::page>
  <div class="space-y-6" wire:poll.2s="pollLogs">
    <div class="text-center">
      <h2 class="text-2xl font-bold tracking-tight">Software Update</h2>
      <p class="text-sm text-gray-500">{{ __('Manage your version and keep Lakasir up to date.') }}</p>
    </div>

    <div class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <p class="text-sm text-gray-500">{{ __('Current Version') }}</p>
          <p class="font-mono text-lg font-semibold text-primary-600 dark:text-primary-400">
            v{{ $currentVersion }}
          </p>
        </div>

        <div>
          <p class="text-sm text-gray-500">{{ __('Latest Version') }}</p>
          <p class="font-mono text-lg font-semibold">
            v{{ $latestVersion }}

            @if ($updateAvailable)
              <span
                class="ml-2 inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                <x-heroicon-o-arrow-up class="mr-1 h-4 w-4" />
                {{ __('Update Available') }}
              </span>
            @else
              <span
                class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-300">
                {{ __('Up to Date') }}
              </span>
            @endif
          </p>
        </div>
      </div>
    </div>

    @if (!empty($changelog))
      <div x-data="{ expanded: false }" class="rounded-xl bg-white p-6 shadow dark:bg-gray-900">
        <h3 class="mb-2 text-lg font-semibold">Changelog</h3>

        <div class="prose max-w-none dark:prose-invert">
          {!! \Illuminate\Support\Str::markdown(
              implode(
                  "\n",
                  collect($changelog)->when(!($showAll = false), fn($lines) => $lines->take(15))->toArray(),
              ),
          ) !!}
        </div>

        @if (count($changelog) > 15)
          <div x-show="!expanded" class="mt-4">
            <button x-on:click="expanded = true" class="text-sm text-primary-600 hover:underline">
              {{ __('Read more') }}
            </button>
          </div>

          <div x-show="expanded" x-cloak class="prose mt-4 max-w-none dark:prose-invert">
            {!! \Illuminate\Support\Str::markdown(implode("\n", collect($changelog)->skip(15)->toArray())) !!}
            <button x-on:click="expanded = false" class="mt-4 block text-sm text-primary-600 hover:underline">
              {{ __('Show less') }}
            </button>
          </div>
        @endif
      </div>
    @endif
    <div class="flex gap-x-2">
      @if ($updateAvailable && $currentVersion != 'Development')
        @can('can update app')
          <form wire:submit.prevent="updateApp">
            <x-filament::button color="primary" icon="heroicon-o-arrow-down-tray" type="submit"
              wire:loading.attr="disabled" wire:target="updateApp">
              <span wire:loading.remove wire:target="updateApp" x-show="!$wire.updateLog">{{ __('Download & Install v') }}
                {{ $latestVersion }}</span>
              <span wire:loading.flex wire:target="updateApp" class="items-center space-x-2">
                <span>{{ __('Downloading') }}...</span>
              </span>
              <span wire:show="updateLog" class="items-center space-x-2">
                <span>{{ __('Downloading') }}...</span>
              </span>
            </x-filament::button>
          </form>
        @endcan
      @else
        @if ($hasPreviousVersion)
          @can('can restore app')
            <x-filament::button color="secondary" wire:click.prevent="restoreApp" wire:loading.attr="disabled"
              wire:target="restoreApp">
              <div class="flex gap-x-1">
                <div>
                  <x-heroicon-o-arrow-uturn-left class="h-5 w-5 text-black dark:text-white" />
                </div>
                <div>
                  <span wire:loading.remove wire:target="restoreApp"
                    class="text-black dark:text-white">{{ __('Restore Previous Version') }}</span>
                  <span wire:loading.flex wire:target="restoreApp"
                    class="items-center space-x-2 text-black dark:text-white">
                    <span>{{ __('Restoring') }}...</span>
                  </span>
                </div>
              </div>
            </x-filament::button>
          @endcan
        @endif
        <x-filament::button disabled>
          @if ($currentVersion != 'Development')
            {{ __('You\'re on the latest version') }}
          @else
            {{ __('You\'re on the development version') }}
          @endif
        </x-filament::button>
      @endif
    </div>
    <div class="mt-4 h-96 overflow-y-auto rounded bg-gray-900 p-4 font-mono text-sm text-green-400"
      wire:show="updateLog">
      @foreach (explode("\n", $updateLog) as $line)
        @if (trim($line))
          <div>{{ $line }}</div>
        @endif
      @endforeach
    </div>
  </div>

  <div x-data="{
      isUpdating: false,
      init() {
          window.addEventListener('beforeunload', e => {
              if (this.isUpdating) {
                  e.preventDefault();
                  e.returnValue = 'Update in progress. Are you sure you want to leave?';
              }
          });

          document.addEventListener('click', e => {
              if (this.isUpdating && e.target.closest('a, button') && !e.target.closest('form')) {
                  e.preventDefault();
              }
          }, true);
      }
  }" x-init="init()">
    <div x-show="isUpdating" x-cloak
      class="fixed inset-0 z-[1000] flex items-center justify-center bg-black bg-opacity-70">
      <div class="space-y-4 px-6 text-center">
        <x-heroicon-o-arrow-path class="mx-auto h-10 w-10 text-white" />
        <h2 class="text-lg font-semibold text-white">System is updating...</h2>
        <p class="text-sm text-white">
          {{ __('Please don’t close this page or navigate away while the update is in progress.') }}</p>
      </div>
    </div>

    <div x-ref="content" wire:loading.class="hidden" wire:loading.class.remove="block">
      <!-- TODO: add the content for preventing user click navigation -->
    </div>

    <div wire:loading wire:target="updateApp" style="display: none;"></div>
  </div>

</x-filament::page>
