/// <reference types="vite/client" />
declare module "uuid";
declare module "vue-i18n";
declare module "@/components";

interface Window extends Window {
  FB: any;
  google: any;
}

interface ImportMetaEnv {
  readonly FB_ACCOUNT_APP: string;
  readonly GOOGLE_ACCOUNT_APP: string;
  readonly GOOGLE_ACCOUNT_CLIENT: string;
}

interface ImportMeta {
  readonly env: ImportMetaEnv;
}
