
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
    <div id="printable-element" class="max-w-full space-y-2">
      @if($reports)
        @include('reports.partials.cashier-data', [
          'header' => $reports['header'],
          'reports' => $reports['reports'],
          'footer' => $reports['footer'],
        ])
      @endif
    </div>
</x-filament-panels::page>
@script()
<script>
  document.getElementById('print-btn').addEventListener('click', () => {
    const printContents = document.getElementById("printable-element").innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    window.location.reload();
  });
</script>
@endscript
