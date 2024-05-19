import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
  presets: [preset],
  content: [
    './vendor/awcodes/filament-table-repeater/resources/**/*.blade.php',
    './app/Filament/Tenant/**/*.php',
    './resources/views/filament/tenant/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
  ],
}
