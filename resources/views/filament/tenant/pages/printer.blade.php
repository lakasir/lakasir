<x-filament-panels::page>
  <x-filament-panels::form
    id="form"
    wire:key="{{ 'forms.' . $this->getFormStatePath() }}">
    {{ $this->form }}

  <x-filament-panels::form.actions
    :actions="$this->getCachedFormActions()"
    :full-width="$this->hasFullWidthFormActions()"
    />
  </x-filament-panels::form>
</x-filament-panels::page>
<script>
  async function fetchTheUsb() {
    let selectedDevice = null;
    try {
      selectedDevice = await navigator.usb.requestDevice({ filters: [] });
      await selectedDevice.open();
      await selectedDevice.selectConfiguration(1);
      await selectedDevice.claimInterface(0);

      localStorage.setItem("printer", JSON.stringify({
        name: selectedDevice.productName,
        vendorId: selectedDevice.vendorId
      }))
      console.log('USB printer selected:', selectedDevice.productName);
    } catch (error) {
      console.error(error);
    }

  }
</script>

