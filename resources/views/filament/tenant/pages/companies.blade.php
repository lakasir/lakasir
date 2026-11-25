<x-filament-panels::page>
  <x-filament::section>
    <x-slot name="heading">
      {{ __('Your shops') }}
    </x-slot>

    <div class="divide-y divide-gray-200 dark:divide-white/10">
      @foreach ($this->companies as $company)
        <div class="flex items-center justify-between gap-4 py-3">
          <div class="min-w-0">
            <p class="truncate font-medium text-gray-950 dark:text-white">
              {{ $company->name }}
            </p>

            <p class="text-sm text-gray-500 dark:text-gray-400">
              @if ($company->isCurrent())
                {{ __('Current shop') }}
              @elseif ($company->onTrial())
                {{ __('Trial') }} &middot; {{ trans_choice(':count day left|:count days left', $company->trialDaysLeft(), ['count' => $company->trialDaysLeft()]) }}
              @elseif ($company->trialExpired())
                {{ __('Trial ended') }}
              @elseif (! $company->enabled)
                {{ __('Disabled') }}
              @else
                {{ __('Available') }}
              @endif
            </p>
          </div>

          <x-filament::button
            size="sm"
            color="gray"
            :disabled="$company->isCurrent()"
            wire:click="switchTo({{ $company->id }})"
          >
            {{ __('Open') }}
          </x-filament::button>
        </div>
      @endforeach
    </div>
  </x-filament::section>
</x-filament-panels::page>
