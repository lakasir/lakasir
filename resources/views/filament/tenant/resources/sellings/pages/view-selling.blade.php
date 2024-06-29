<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif

    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers
            :active-manager="$this->activeRelationManager"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        />
    @endif
</x-filament-panels::page>
@script
<script>
  document.getElementById('usbButton').addEventListener('click', async () => {
    let selling = @js($record);
    let about = @js($about);

    try {
      if (localStorage.printer == undefined) {
        new FilamentNotification()
          .title('@lang('You should choose the printer first in printer setting')')
          .danger()
          .actions([
            new FilamentNotificationAction('Setting')
              .icon('heroicon-o-cog-6-tooth')
              .button()
              .url('/member/printer'),
          ])
          .send()
      } else {
        const printer = new Printer();
        printer.font('a')
          .size(1)
          .align('center')
          .text('Toko Mitra Susu')
          .size(0)
          .text('Jl. cipinang raya no 156')
          .text('-------------------------------')
          .tableCustom([
            { text: 'Cashier', align: 'LEFT', width: 0.33, style: 'B' },
            { text: 'Nama Kasir', align: 'RIGHT', width: 0.33 }
          ])
          .tableCustom([
            { text: 'Payment method', align: 'LEFT', width: 0.33, style: 'B' },
            { text: 'Cash', align: 'RIGHT', width: 0.33 }
          ])
          .text('-------------------------------')
          .tableCustom([
            { text: 'Bantak', align: 'LEFT', width: 0.33},
            { text: moneyFormat(2000) + ' x 1', align: 'RIGHT', width: 0.33, style: 'B'}
          ])
          .align('right')
          .text(moneyFormat(2000))
          .align('left')
          .table(['printer', '    ', moneyFormat(5000)])
          .tableCustom([
            { text: 'Printer', align: 'LEFT', width: 1},
            { text: moneyFormat(5000) + ' x 1', align: 'RIGHT', width: 0.33, style: 'B'}
          ])
          .align('right')
          .text(moneyFormat(2000))
          .text('-------------------------------')
          .tableCustom([
            { text: 'Subtotal', align: 'LEFT', width: 1, style: 'B'},
            { text: moneyFormat(5000) + ' x 1', align: 'RIGHT', width: 0.33, style: 'B'}
          ])
          .tableCustom([
            { text: 'Tax', align: 'LEFT', width: 0.33, style: 'B'},
            { text: moneyFormat(5000) + ' x 1', align: 'RIGHT', width: 0.33, style: 'B'}
          ])
          .tableCustom([
            { text: 'Total price', align: 'LEFT', width: 0.33, style: 'B'},
            { text: moneyFormat(5000) + ' x 1', align: 'RIGHT', width: 0.33, style: 'B'}
          ])
          .text('\n\n');

        printToUSBPrinter(printer.getCommands());
      }
    } catch (error) {
      console.error(error);
    }
  });
</script>
@endscript
