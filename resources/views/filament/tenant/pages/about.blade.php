<div class="w-10/12 mx-auto">
  <x-filament-panels::page>
    <x-filament-panels::form
      id="form"
      wire:key="{{ 'forms.' . $this->getFormStatePath() }}"
      >
      {{ $this->form }}

      <x-filament-panels::form.actions
        :actions="$this->getCachedFormActions()"
        :full-width="$this->hasFullWidthFormActions()"
        />
      </x-filament-panels::form>
    </x-filament-panels::page>
</div>
