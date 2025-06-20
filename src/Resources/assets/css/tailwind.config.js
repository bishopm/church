import preset from '../../../../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './vendor/bishopm/church/src/Filament/**/*.php',
        './vendor/bishopm/church/src/Resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/mansoor/filament-unsplash-picker/resources/views/**/*.blade.php',
        './vendor/guava/calendar/resources/**/*.blade.php'
    ],
}
