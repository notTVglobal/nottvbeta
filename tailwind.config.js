/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                '69': '17rem',
                '76': '19rem',
                '78': '19.25rem',
            },
            colors: {
                tan: {
                    600: '#d2b48c', // A light brown tan color
                    800: '#a67b5b'  // A darker brown tan color
                }
            },
        },
        listStyleType: {
            disc: 'disc',
            // ... other styles
        }
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography'), require("daisyui")],
};
