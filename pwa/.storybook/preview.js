import { app } from '@storybook/vue3';
import { createPinia } from 'pinia';
import router from '../src/router'
import i18n from '../src/translations'
import { withTests } from "@storybook/addon-jest";
import results from './.jest-test-results.json';

const pinia = createPinia();
app.use(i18n)
app.use(pinia);
app.use(router)

/**
 * Adding a global decorator to stories.
 * Test injection is provided.
 */
export const decorators = [
  withTests({
    results,
  }),(story, { globals: { locale } }) => {
  return {
    components: { story },
    template: '<story />',
  };
}];


export const parameters = {
  actions: { argTypesRegex: "^on[A-Z].*" },
  status: {
    statuses: {
      todo: {
        background: '#e6a23c',
        color: '#ffffff',
        description: 'This component is todo',
      },
    },
  },
  controls: {
    matchers: {
      color: /(background|color)$/i,
      date: /Date$/,
    },
  },
}