import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Tenant/**/*.php',
        './resources/views/filament/tenant/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
