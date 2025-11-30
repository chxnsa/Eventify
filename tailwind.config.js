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
                // Primary Blue - Eventify brand color
                primary: {
                    50: '#e6f2ff',
                    100: '#b3d9ff',
                    200: '#80bfff',
                    300: '#4da6ff',
                    400: '#1a8cff',
                    500: '#0073e6',
                    600: '#006ACD', // Main brand color
                    700: '#0055a3',
                    800: '#004080',
                    900: '#002b5c',
                },
                // Accent colors
                accent: {
                    yellow: '#F59E0B',
                    green: '#10B981',
                    red: '#EF4444',
                },
            },
            fontFamily: {
                // Primary font - Raleway
                sans: ['Raleway', ...defaultTheme.fontFamily.sans],
                // Secondary font - Poppins
                poppins: ['Poppins', ...defaultTheme.fontFamily.sans],
                // Explicitly define Raleway
                raleway: ['Raleway', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                '4xl': '2rem',
                '5xl': '2.5rem',
            },
            boxShadow: {
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'navbar': '0 4px 20px rgba(0, 0, 0, 0.1)',
            },
        },
    },

    plugins: [forms],
};
