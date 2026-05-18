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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                gopher: ['Gopher', 'sans-serif'],
            },
            colors: {
                'electric-lime': '#C9FF00',
                'night-rider': '#111111',
                'white-smoke': '#FFFFFF',
                'paradiso': '#3B8080',
                'turtle-green': '#283300',
            },
        },
    },

    plugins: [forms],
};
