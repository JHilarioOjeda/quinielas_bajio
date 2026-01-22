import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                //primaries
                'primarycolor' : '#408331',
                'primaryhovercolor' : '#3f9b2b',

                //secundaries
                'secondarycolor' : '#e38d0a',
                'secondaryhcolor' : '#e69823',


                //others
                'dangercolor' : '#e02424',
                'dangerhcolor' : '#ff3333',

            }
        },
    },

    plugins: [forms, typography],
};
