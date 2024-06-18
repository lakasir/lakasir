import defaultTheme from "tailwindcss/defaultTheme";
import preset from './vendor/filament/support/tailwind.config.preset'

import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
  presets: [preset],
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    './resources/views/filament/**/*.blade.php',
    './app/Filament/**/*.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans],
      },
      colors: {
        lakasir: {
          primary: "#FF6600",
        }
      },
    },
  },

  plugins: [forms],
};
