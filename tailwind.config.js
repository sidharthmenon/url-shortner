const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        './vendor/filament/**/*.blade.php', 
        './app/Http/Livewire/**/*.php',
        './app/Traits/**/*.php',
        './app/Providers/**/*.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
    ],
    safelist: [
        {
          pattern: /max-w-\dxl/,
          variants: ['sm', 'md', 'lg', 'xl']
        }
    ],    
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                success: colors.green,
                warning: colors.yellow,
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
