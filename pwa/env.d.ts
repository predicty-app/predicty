/// <reference types="vite/client" />
declare module "uuid"; 
declare module "vue-i18n";
declare module "@/components";

interface Window extends Window {
  FB: any;
  google: any;
}

interface ImportMetaEnv {
  readonly VITE_FB_ACCOUNT_APP: string;
  readonly VITE_GOOGLE_ACCOUNT_APP: string;
  readonly VITE_GOOGLE_ACCOUNT_CLIENT: string;
  readonly VITE_API_ENDPOINT: string;
}

interface ImportMeta {
  readonly env: ImportMetaEnv;
}
