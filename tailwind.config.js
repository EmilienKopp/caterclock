import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.svelte',
    ],

    daisyui: {
        themes: ["forest","light", "dark"],
    },

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    
    plugins: [forms,require("daisyui")],

    safelist: [
        "bg-blue-200",
        "bg-yellow-200",
        "bg-indigo-200",
        "bg-pink-200",
        "bg-green-200",
        "bg-orange-200",
        "bg-purple-200",
        "bg-gray-200",
        "bg-teal-200",
        "bg-red-300",
        "bg-cyan-300",
        "bg-lime-300",
        "bg-amber-300",
        "bg-gray-200",
    ]
};
