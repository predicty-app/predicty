/** @type {import('tailwindcss').Config} */

const t = '#FFFFFF';

module.exports = {
  mode: "jit",
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx,vue}"],
  theme: {
    extend: {
      top: {
        dynamic: "var(--top)"
      },
      left: {
        dynamic: "var(--left)"
      },
      width: {
        calc: "calc(100vw)",
        dynamic: "var(--height)",
        max: "calc(100vw + 100vw + 100vw)"
      },
      content: {
        dynamic: "var(--content)",
        calc: "calc(100vw)"
      },
      height: {
        calc: "calc(100vh - 60px)",
        dynamic: "var(--height)"
      },
      backgroundColor: {
        dynamic: "var(--background)"
      },
      borderColor: {
        dynamic: "var(--border)"
      },
      gridColumnStart: {
        dynamic: "var(--start)"
      },
      gridColumnEnd: {
        dynamic: "var(--end)"
      },
      fill: {
        dynamic: "var(--fill)"
      },
      textColor: {
        dynamic: "var(--color)"
      },
      boxShadowColor: {
        dynamic: "var(--shadow)"
      },
      backgroundImage: {
        'gradient-linear': 'linear-gradient(var(--tw-gradient-from), var(--tw-gradient-to))',
      }
    },
    colors: {
      violet: {
        100: "#2E0C63"
      },
      basic: {
        white: "#FFFFFF",
        black: "#000000",
      },
      gray: {
        100: "#F1F3F6",
        200: "#FDFCF5",
        300: "#EDF0F3",
        400: "#DBDBDB",
        500: "#D9D9D9",
        600: "#CDCDCD",
        700: "#B5B5B5",
        800: "#A6A6A6",
        900: "#8A8A8A",
        1000: "#4E5B72",
        1100: "#404040",
        1200: "#272727",
        1300: "#111111"
      },
      blue: {
        100: "#E0E3FA",
        200: "#89B3FF",
        300: "#4184FF",
      },
      red: {
        100: "#EB7A7A",
      },
      orange: {
        100: "#FFAE4F"
      },
      green: {
        100: "#bae7c2",
        200: "#60D073",
        300: "#66CC66",
        400: "#41cb59",
        500: "#4cb25d",
        600: "#80C9B9",
      },
    },
    boxShadow: {
      bottombar: "0px -5px 30px rgba(0, 0, 0, 0.15)"
    },
    backgroundImage: {
      light:
        "repeating-linear-gradient( to right, #fff 0 calc((100% / 7) - 1px), rgb(233, 233, 233) calc((100% / 7) - 1px) calc(100% / 7) )",
      dark: "repeating-linear-gradient( to right, #f4f4f6 0 calc((100% / 7) - 1px), rgb(233, 233, 233) calc((100% / 7) - 1px) calc(100% / 7) )",
      one: "repeating-linear-gradient(to right,rgb(233, 233, 233) 0 1px,#fff 1px calc(v-bind(scaleLines) / 7),rgb(233, 233, 233) calc(v-bind(scaleLines) / 7) calc((v-bind(scaleLines) / 7) + 1px),#fff calc((v-bind(scaleLines) / 7) + 1px) calc(2 * (v-bind(scaleLines) / 7)), rgb(233, 233, 233) calc(2 * (v-bind(scaleLines) / 7)) calc(2 * (v-bind(scaleLines) / 7) + 1px),#fff calc(2 * (v-bind(scaleLines) / 7) + 1px) calc(3 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(3 * (v-bind(scaleLines) / 7)) calc(3 * (v-bind(scaleLines) / 7) + 1px),#fff calc(3 * (v-bind(scaleLines) / 7) + 1px) calc(4 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(4 * (v-bind(scaleLines) / 7)) calc(4 * (v-bind(scaleLines) / 7) + 1px),#fff calc(4 * (v-bind(scaleLines) / 7) + 1px) calc(5 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(5 * (v-bind(scaleLines) / 7)) calc(5 * (v-bind(scaleLines) / 7) + 1px),#fff calc(5 * (v-bind(scaleLines) / 7) + 1px) calc(6 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(6 * (v-bind(scaleLines) / 7)) calc(6 * (v-bind(scaleLines) / 7) + 1px),#fff calc(6 * (v-bind(scaleLines) / 7) + 1px) calc(7 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(7 * (v-bind(scaleLines) / 7)) calc(7 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(7 * (v-bind(scaleLines) / 7) + 1px) calc(8 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(8 * (v-bind(scaleLines) / 7)) calc(8 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(8 * (v-bind(scaleLines) / 7) + 1px) calc(9 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(9 * (v-bind(scaleLines) / 7)) calc(9 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(9 * (v-bind(scaleLines) / 7) + 1px) calc(10 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(10 * (v-bind(scaleLines) / 7)) calc(10 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(10 * (v-bind(scaleLines) / 7) + 1px) calc(11 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(11 * (v-bind(scaleLines) / 7)) calc(11 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(11 * (v-bind(scaleLines) / 7) + 1px) calc(12 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(12 * (v-bind(scaleLines) / 7)) calc(12 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(12 * (v-bind(scaleLines) / 7) + 1px) calc(13 * (v-bind(scaleLines) / 7)),rgb(233, 233, 233) calc(13 * (v-bind(scaleLines) / 7)) calc(13 * (v-bind(scaleLines) / 7) + 1px),#f4f4f6 calc(13 * (v-bind(scaleLines) / 7) + 1px) calc(14 * (v-bind(scaleLines) / 7)))"
    },
  },
  variants: {
    extend: {}
  },
  plugins: []
};
