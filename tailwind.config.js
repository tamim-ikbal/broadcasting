import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: ['./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php', './storage/framework/views/*.php', './resources/views/**/*.blade.php',],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            }, keyframes: {
                typing: {
                    "0%": {
                        width: "0%", visibility: "hidden"
                    }, "100%": {
                        width: "100%"
                    }
                }, blink: {
                    "50%": {
                        borderColor: "transparent"
                    }, "100%": {
                        borderColor: "white"
                    }
                }
            }, animation: {
                typing: "typing .5s steps(20) infinite alternate, blink .7s infinite"
            }
        }, container: {
            center: true, padding: '2rem', screens: {
                sm: '100%', md: '100%', lg: '100%', xl: '80rem', '2xl': '80rem',
            },
        }
    },

    plugins: [forms],
};
