/// <reference types="vite/client" />
declare module "uuid";
declare module "vue-i18n";
declare module '@/components'

interface ImportMetaEnv {}

interface ImportMeta {
  readonly env: ImportMetaEnv;
}
