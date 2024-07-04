<x-filament-panels::page>
  <div x-data="printer">
    <x-filament-panels::form
      x-ref="printerForm"
      id="form"
      wire:key="{{ 'forms.' . $this->getFormStatePath() }}">
      {{ $this->form }}

    <x-filament-panels::form.actions
      :actions="$this->getCachedFormActions()"
      :full-width="$this->hasFullWidthFormActions()"
      />
    </x-filament-panels::form>
  </div>
</x-filament-panels::page>
@script()
  <script>
    Alpine.data('printer', () => ({
      init() {
        if(localStorage.printer) {
          const printer = JSON.parse(localStorage.printer);
          $wire.data = {
            ...printer
          }
        }
      },
      fetchDeviceByDriver() {
        if($wire.data.driver == 'bluetooth') {
          this.fetchBluetooth();
        }
        if($wire.data.driver == 'usb') {
          this.fetchTheUsb();
        }
      },
      async fetchTheUsb() {
        let selectedDevice = null;
        try {
          selectedDevice = await navigator.usb.requestDevice({ filters: [] });
          await selectedDevice.open();
          await selectedDevice.selectConfiguration(1);
          await selectedDevice.claimInterface(0);
          $wire.data.printer = selectedDevice.productName;
          $wire.data.printerId = selectedDevice.vendorId;
          console.log('USB printer selected:', selectedDevice.productName);
        } catch (error) {
          console.error(error);
        }
      },
      async fetchBluetooth() {},
      save() {
        $wire.validateInput();
        if(!$wire.data.printer || !$wire.data.name) {
          return;
        }
        localStorage.setItem("printer", JSON.stringify({
          ...$wire.data,
        }))

        new FilamentNotification()
          .title('@lang('Save success')')
          .success()
          .send()
      },
      async test() {
        $wire.validateInput();
        if(!$wire.data.printer || !$wire.data.name) {
          return;
        }
        try {
          const printer = new Printer($wire.data.printerId);
          printerAction = printer.font('a')
            .size(1)
            .align('center')
            .text('Toko Mitra Susu')
            .size(0)
            .text('Jl. cipinang raya no 156');

          if($wire.data.header != undefined) {
            printerAction
              .text($wire.data.header);
          }

          printerAction.align('left')
            .text('-------------------------------')
            .table(['Cashier', 'Nama kasir'])
            .table(['Payment method', 'Cash'])
            .text('-------------------------------')
            .tableCustom([
              { text: 'Test 1'},
              { text: moneyFormat(2000) + ' x 1', style: 'B'}
            ])
            .align('right')
            .text(moneyFormat(2000))
            .tableCustom([
              { text: 'Test 2'},
              { text: moneyFormat(5000) + ' x 1', style: 'B'}
            ])
            .align('right')
            .text(moneyFormat(2000))
            .text('-------------------------------')
            .tableCustom([
              { text: 'Subtotal', style: 'B'},
              { text: moneyFormat(5000) + ' x 1', style: 'B'}
            ])
            .tableCustom([
              { text: 'Tax', style: 'B'},
              { text: moneyFormat(5000) + ' x 1', style: 'B'}
            ])
            .tableCustom([
              { text: 'Total price', style: 'B'},
              { text: moneyFormat(5000) + ' x 1', style: 'B'}
            ])
            .newLine()
            .align('center');
          if($wire.data.footer != undefined) {
            printerAction
              .text($wire.data.footer);
          }
          await printerAction.cut()
            .print();
        } catch (e) {
          console.error(e)
        }
      }
    }))
  </script>
@endscript
