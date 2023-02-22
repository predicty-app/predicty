/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  purge: ['./index.html', './src/**/*.{js,ts,jsx,tsx,vue}'],
  darkMode: false,
  theme: {
    colors: {
      fill: {
        default: '#2E0C63'
      },
      text: {
        input: '#82848A',
        error: '#da2f2f',
        primary: '#2E0C63',
        secondary: '#272727',
      },
      default: {
        border: '#b3b6ba',
        outline: '#b3b6ba'
      },
      logo: {
        green: {
          dark: '#19BE34B2',
          light: '#A0E29E',
        },
        blue: {
          dark: '#195BBEB2',
          light: '#BBE4ED',
        },
        purple: {
          dark: '#BB19BEB2',
          light: '#DBB9EB',
        }
      }
    }
  },
  variants: {
    extend: {},
  },
  plugins: [],
}