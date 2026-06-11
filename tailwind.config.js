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
                sans:    ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta idêntica à Anthropic / Claude (extraída do CSS oficial)
                ivory: {
                    light:  '#faf9f5',   // fundo principal
                    medium: '#f0eee6',   // fundo secundário
                    dark:   '#e8e6dc',   // hover / divisor
                    'faded-10': 'rgba(250,249,245,0.10)',
                    'faded-20': 'rgba(250,249,245,0.20)',
                },
                slate: {
                    dark:    '#141413',  // texto principal
                    medium:  '#3d3d3a',  // texto secundário
                    light:   '#5e5d59',  // texto terciário
                    'faded-10': 'rgba(20,20,19,0.10)',
                    'faded-20': 'rgba(20,20,19,0.20)',
                },
                cloud: {
                    light:  '#d1cfc5',
                    medium: '#b0aea5',
                    dark:   '#87867f',
                },
                // Accent — "clay" é o tom terracota icônico do Claude
                clay:     '#d97757',
                accent:   '#c6613f',
                // Cores complementares da paleta Anthropic
                oat:      '#e3dacc',
                manilla:  '#ebdbbc',
                kraft:    '#d4a27f',
                coral:    '#ebcece',
                cactus:   '#bcd1ca',
                olive:    '#788c5d',
                heather:  '#cbcadb',
                sky:      '#6a9bcc',
            },
        },
    },

    plugins: [forms],
};
