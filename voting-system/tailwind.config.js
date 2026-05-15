import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                nacos: {
                    50: '#f6fbf9',
                    100: '#e9f7ef',
                    200: '#cfeee0',
                    300: '#9fe0c6',
                    400: '#66c1a3',
                    500: '#2f8f6e',
                    600: '#24795b',
                    700: '#1a593f',
                    800: '#113b2b',
                    900: '#0a281c',
                    950: '#051412',
                },
                primary: {
                    50: '#f6fbf9',
                    100: '#e9f7ef',
                    200: '#cfeee0',
                    300: '#9fe0c6',
                    400: '#66c1a3',
                    500: '#2f8f6e',
                    600: '#24795b',
                    700: '#1a593f',
                    800: '#113b2b',
                    900: '#0a281c',
                    950: '#051412',
                },
                accent: {
                    50: '#f2f8f6',
                    100: '#e2f0ea',
                    200: '#c6e2d6',
                    300: '#9bceb7',
                    400: '#5fad8f',
                    500: '#2c7a60',
                    600: '#236552',
                    700: '#1c4f41',
                    800: '#143a30',
                    900: '#0d261f',
                    950: '#071510',
                },
                surface: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                },
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'slide-in-right': 'slideInRight 0.3s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-soft': 'bounceSoft 1s ease-in-out',
                'count-up': 'countUp 0.6s ease-out',
                'hero-float': 'heroFloat 6s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                bounceSoft: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                },
                countUp: {
                    '0%': { opacity: '0', transform: 'scale(0.5)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                heroFloat: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-12px)' },
                },
            },
        },
    },

    plugins: [forms],
};
