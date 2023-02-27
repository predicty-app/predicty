/// <reference types="gapi" />

import fetchInject from "fetch-inject";
import type { StrategyProviders } from "@/services/providers/ContextProviders";

export default class GoogleProviderStrategy implements StrategyProviders {
  #scopePrivilages: string[] = [];
  #libraryPath = "https://accounts.google.com/gsi/client";

  public async initProvider() {
    await fetchInject([this.#libraryPath]);
    window.google.accounts.id.initialize({
      client_id: import.meta.env.GOOGLE_ACCOUNT_CLIENT,
      callback: () => this.#handleCredentialResponse(),
    });
  }

  #handleCredentialResponse() {
    console.log("test");
  }
}
