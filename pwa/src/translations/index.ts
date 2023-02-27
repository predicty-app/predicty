import { createI18n } from "vue-i18n";

/**
 * Function to get dynamical transalations for application.
 * @returns {Object}
 */
function getTranslationsNames() {
  const modules = import.meta.glob("./*.json");
  const names = Object.keys(modules).map((name) =>
    name.replace("./", "").replace(".json", "")
  );

  const messages: { [props: string]: any } = {};
  names.forEach(
    async (name) =>
      // @ts-ignore
      (messages[name] = (await modules[`./${name}.json`]()).default)
  );

  return messages;
}

const i18n = createI18n({
  locale: "en-GB",
  legacy: false,
  fallbackLocale: "en-GB",
  messages: getTranslationsNames(),
});

export default i18n;
