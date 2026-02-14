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
            colors: {
                emerald: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669', // Primary Green
                    700: '#047857',
                    800: '#065f46', // Dark Green
                    900: '#064e3b', // Deepest Green
                    950: '#022c22',
                },
                gold: {
                    50: '#fbf8eb',
                    100: '#f5efc8',
                    200: '#ede092',
                    300: '#e4cd56',
                    400: '#deb82a',
                    500: '#d49e15', // Primary Gold
                    600: '#b67a10',
                    700: '#915810',
                    800: '#784614',
                    900: '#663b15',
                }
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', 'serif'], // Elegant serif for headings
            },
            backgroundImage: {
                'islamic-pattern': "url('https://www.transparenttextures.com/patterns/arabesque.png')", // Placeholder for pattern
            }
        },
    },

    plugins: [forms],
};
