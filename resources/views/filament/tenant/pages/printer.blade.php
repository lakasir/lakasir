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
        if(localStorage.printerApiUrl) {
          $wire.data = {
            name: 'API Printer',
            driver: 'api',
            printer: 'API Printer Service',
            printerId: localStorage.printerApiUrl
          }
        }
      },
      fetchDeviceByDriver() {
        if($wire.data.driver == 'api') {
          $wire.data.printer = 'API Printer Service';
          $wire.data.printerId = 'http://localhost:8888/print';
        }
      },
      async fetchTheUsb() {
        this.fetchDeviceByDriver();
      },
      async fetchBluetooth() {},
      save() {
        $wire.validateInput();
        if(!$wire.data.printer || !$wire.data.name) {
          return;
        }
        localStorage.setItem("printerApiUrl", $wire.data.printerId || 'http://localhost:8888/print');

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
          const printer = new Printer($wire.data.printerId || 'http://localhost:8888/print');
          printer.text_content = 'Test Receipt';
          printer.items = [
            'Toko Mitra Susu',
            'Jl. cipinang raya no 156',
            '-------------------------------',
            'Cashier - Nama kasir',
            'Payment method - Cash',
            '-------------------------------',
            'Test 1 - ' + moneyFormat(2000) + ' x 1',
            '  Total: ' + moneyFormat(2000),
            'Test 2 - ' + moneyFormat(5000) + ' x 1',
            '  Total: ' + moneyFormat(5000),
            '-------------------------------',
            'Subtotal: ' + moneyFormat(7000),
            'Tax: 10%',
            'Total price: ' + moneyFormat(7700),
          ];

          await printer.print();
          
          new FilamentNotification()
            .title('@lang('Test print sent successfully')')
            .success()
            .send();
        } catch (e) {
          console.error(e);
          new FilamentNotification()
            .title('@lang('Test print failed')')
            .danger()
            .send();
        }
      }
    }))
  </script>
@endscript
