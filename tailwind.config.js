import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // ...
    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#2ec2c3', // Color extraído
                    dark: '#229e9f',    // Tono más oscuro para hover/focus
                    light: '#e6f8f8',   // Tono muy claro para textos o fondos suaves
                },
            },
        },
    },
};
