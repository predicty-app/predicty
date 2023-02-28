/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: "jit",
  purge: ["./index.html", "./src/**/*.{js,ts,jsx,tsx,vue}"],
  darkMode: false,
  theme: {
    colors: {
      upload: {
        background: "#FFFFFF",
        border: "#E0E3FA",
        text: "#2E0C63",
        button: {
          background: "#2E0C63",
          text: "#FFFFFF"
        }
      },
      layout: {
        onboarding: {
          background: "#F5F6FD",
          border: "#E0E3FA",
        },
      },
      card: {
        border: {
          default: "#E0E3FA",
          success: "#19BE34"
        },
        background: {
          default: "#FFFFFF",
          success: "#60D073",
        },
        text: {
          default: "#000000",
          success: "#FFFFFF",
        }
      },
      fill: {
        default: "#2E0C63",
      },
      divider: {
        default: "#D9D9D9",
      },
      tag: {
        border: {
          default: "#E0E3FA",
          success: "#19BE34",
          primary: "#19BE34",
        },
        background: {
          default: "#E0E3FA",
          success: "#E9FFF0",
          primary: "#60D073",
        },
        text: {
          default: "#2E0C63",
          success: "#19BE34",
          primary: "#FFFFFF",
        },
      },
      progress: {
        default: "#E0E3FA",
        active: "#2E0C63",
      },
      button: {
        default: "#F5F6FD",
        success: "#60D073",
        border: "#E0E3FA",
        disabled: "#D0D0D0",
        hover: {
          default: "#e4e7f7",
          success: "#41cb59",
        },
        active: {
          default: "#e2e4ec",
          success: "#4cb25d",
        },
      },
      text: {
        white: "#FFFFFF",
        input: "#82848A",
        error: "#da2f2f",
        primary: "#2E0C63",
        secondary: "#272727",
      },
      default: {
        border: "#b3b6ba",
        outline: "#b3b6ba",
      },
      logo: {
        green: {
          dark: "#19BE34B2",
          light: "#A0E29E",
        },
        blue: {
          dark: "#195BBEB2",
          light: "#BBE4ED",
        },
        purple: {
          dark: "#BB19BEB2",
          light: "#DBB9EB",
        },
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
};
