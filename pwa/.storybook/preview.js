import { app } from '@storybook/vue3';
import { createPinia } from 'pinia';
import router from '../src/router'

const pinia = createPinia();
app.use(pinia);
app.use(router)

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