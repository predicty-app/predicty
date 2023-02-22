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
      progress: {
        default: '#E0E3FA',
        active: '#2E0C63'
      },
      button: {
        default: '#F5F6FD',
        success: '#60D073',
        hover: {
          default: '#e4e7f7',
          success: '#41cb59',
        },
        active: {
          default: '#e2e4ec',
          success: '#4cb25d',
        }
      },
      text: {
        white: '#FFFFFF',
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