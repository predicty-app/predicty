const path = require("path");
const url = require("url");
const tsconfigPaths = require("vite-tsconfig-paths").default;

module.exports = {
  "stories": [
    "../src/**/*.stories.mdx",
    "../src/**/*.stories.@(js|jsx|ts|tsx)"
  ],
  "addons": [
    "@storybook/addon-links",
    "@storybook/addon-essentials",
    "@storybook/addon-interactions",
    "@etchteam/storybook-addon-status",
  ],
  "framework": "@storybook/vue3",
  "core": {
    "builder": "@storybook/builder-vite"
  },
  "features": {
    "storyStoreV7": true,
    "interactionsDebugger": true,
  },
  viteFinal(config) {
    return {
      ...config,
      resolve: {
        alias: {
          ...config.resolve.alias,
          '@': path.resolve(__dirname, '../src'),
          'vue-i18n': 'vue-i18n/dist/vue-i18n.cjs.js'
        }
      },
      plugins: [...config.plugins, tsconfigPaths()],
    };
  },
}