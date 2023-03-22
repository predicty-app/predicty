/* eslint-env node */
require("@rushstack/eslint-patch/modern-module-resolution");

module.exports = {
  root: true,
  ignorePatterns: ["postcss.config.js", "tailwind.config.js", "vite.config.ts"],
  extends: [
    "plugin:vue/vue3-essential",
    "eslint:recommended",
    "@vue/eslint-config-typescript",
    "@vue/eslint-config-prettier"
  ],
  parserOptions: {
    ecmaVersion: "latest"
  }
};
