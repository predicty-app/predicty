import { createApp } from "vue";
import { createPinia } from "pinia";

import i18n from "./translations";
import App from "./App.vue";
import router from "./router";

import "./assets/scss/index.scss";

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(i18n);
app.mount("#app");
