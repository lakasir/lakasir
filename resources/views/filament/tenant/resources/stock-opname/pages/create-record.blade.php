<x-filament-panels::page
    @class([
        'fi-resource-create-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>
    <x-filament-panels::form
        id="form"
        :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="create"
    >
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <x-filament-panels::page.unsaved-data-changes-alert />
</x-filament-panels::page>

@script()
<script>
  let barcodeData = '';
  let barcodeTimeout;
  let scannerEnabled = true;
  document.addEventListener('keypress', (event) => {
    if (!scannerEnabled) {
      return;
    }
    if (barcodeTimeout) {
      clearTimeout(barcodeTimeout);
    }

    if (event.key === 'Enter') {
      console.log('Barcode scanned:', barcodeData);
      // $wire.addCartUsingScanner(barcodeData);

      barcodeData = '';
      scannerEnabled = false;

      setTimeout(() => {
        scannerEnabled = true;
      }, 1000);
    } else {
      barcodeData += event.key;
    }

    barcodeTimeout = setTimeout(() => {
      barcodeData = '';
    }, 500);
  });
</script>
@endscript
