import defaultTheme from "tailwindcss/defaultTheme";
import preset from './vendor/filament/support/tailwind.config.preset'

import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  presets: [preset],
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    './resources/views/filament/**/*.blade.php',
    './app/Filament/**/*.php',
    './vendor/filament/**/*.blade.php',
    './vendor/aymanalhattami/**/*.blade.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Inter", ...defaultTheme.fontFamily.sans],
      },
      colors: {
        lakasir: {
          primary: "#FF6600",
        },
        border: "hsl(var(--border))",
        input: "hsl(var(--input))",
        ring: "hsl(var(--ring))",
        background: "hsl(var(--background))",
        foreground: "hsl(var(--foreground))",
        primary: {
          DEFAULT: "hsl(var(--primary))",
          foreground: "hsl(var(--primary-foreground))",
        },
        secondary: {
          DEFAULT: "hsl(var(--secondary))",
          foreground: "hsl(var(--secondary-foreground))",
        },
        destructive: {
          DEFAULT: "hsl(var(--destructive))",
          foreground: "hsl(var(--destructive-foreground))",
        },
        muted: {
          DEFAULT: "hsl(var(--muted))",
          foreground: "hsl(var(--muted-foreground))",
        },
        accent: {
          DEFAULT: "hsl(var(--accent))",
          foreground: "hsl(var(--accent-foreground))",
        },
        popover: {
          DEFAULT: "hsl(var(--popover))",
          foreground: "hsl(var(--popover-foreground))",
        },
        card: {
          DEFAULT: "hsl(var(--card))",
          foreground: "hsl(var(--card-foreground))",
        },
      },
      boxShadow: {
        'skeuo': '0 2px 0 0 rgba(255, 102, 0, 0.8), 0 1px 2px 0 rgba(0, 0, 0, 0.1)',
        'skeuo-sm': '0 1px 0 0 rgba(255, 102, 0, 0.8)',
        'skeuo-gray': '0 2px 0 0 rgba(200, 200, 200, 1), 0 1px 2px 0 rgba(0, 0, 0, 0.1)',
        'skeuo-pressed': 'inset 0 3px 5px rgba(0, 0, 0, 0.15)',
        'skeuo-card': '0 4px 0 0 rgba(230, 230, 230, 1), 0 1px 3px 0 rgba(0, 0, 0, 0.1)',
      },
      borderRadius: {
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
      },
    },
  },

  plugins: [forms],
};
