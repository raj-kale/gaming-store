import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    // Laravel & storage views
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',

    // Blade templates
    './resources/views/**/*.blade.php',

    // JS entry files (important because app.js imports the CSS)
    './resources/js/**/*.js',
    './resources/js/**/*.vue',

    // Any other files that might contain tailwind classes
    './resources/**/*.php',
    './resources/css/**/*.css'
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [forms],
};
