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
        printToUSBPrinter(selling, about, true);
      }
    } catch (error) {
      console.error(error);
    }
  });
</script>
@endscript
