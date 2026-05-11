import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Primary (Blue) - Harvestly & Global
                'tx-primary': 'var(--tx-primary)',
                'tx-primary-light': 'var(--tx-primary-light)',
                'tx-primary-mid': 'var(--tx-primary-mid)',
                
                // Secondary (Pink) - Naturea
                'tx-secondary': 'var(--tx-secondary)',
                'tx-secondary-light': 'var(--tx-secondary-light)',
                'tx-secondary-mid': 'var(--tx-secondary-mid)',

                // Tertiary (Lavender) - Skin School
                'tx-tertiary': 'var(--tx-tertiary)',
                'tx-tertiary-light': 'var(--tx-tertiary-light)',
                'tx-tertiary-mid': 'var(--tx-tertiary-mid)',

                // Quaternary (Sage/Mint) - Karebla
                'tx-quaternary': 'var(--tx-quaternary)',
                'tx-quaternary-light': 'var(--tx-quaternary-light)',
                'tx-quaternary-mid': 'var(--tx-quaternary-mid)',

                // Backgrounds
                'tx-bg': '#FAF9F7',
                'tx-white': '#FFFFFF',
            }
        },
    },

    plugins: [forms],
};
