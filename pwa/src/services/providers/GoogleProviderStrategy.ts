/// <reference types="gapi" />

import fetchInject from "fetch-inject";
import type { StrategyProviders } from "@/services/providers/ContextProviders";

type GoogleResponseType = {
  clientId: string;
  client_id: string;
  credential: string;
  select_by: string;
};

export default class GoogleProviderStrategy implements StrategyProviders {
  /**
   * @var {string}[] #scopeAccessPrivilages
   * @private
   */
  #scopeAccessPrivilages = [
    "https://www.googleapis.com/auth/analytics.readonly"
  ];

  /**
   * @var {string} #accessToken
   * @private
   */
  #accessToken = "";

  #libraryPath = "https://accounts.google.com/gsi/client";

  public async initProvider() {
    await fetchInject([this.#libraryPath]);
  }

  /**
   * Method to send request permission to current provider.
   * @return {Promise<void>}
   */
  public async sendRequestPermission(): Promise<void> {
    return new Promise((resolve) => {
      window.google.accounts.id.initialize({
        client_id: import.meta.env.VITE_GOOGLE_ACCOUNT_CLIENT,
        callback: (response) => {
          this.#setAccessToken(response);
          resolve();
        },
        scope: this.#scopeAccessPrivilages
      });
      window.google.accounts.id.prompt();
    });
  }

  /**
   * Method to get access token from request.
   * @return {string}
   */
  public getAccessToken(): string {
    return this.#accessToken;
  }

  /**
   * Method to set access token from request permission.
   * @param {GoogleResponseType} response
   */
  #setAccessToken(response: GoogleResponseType) {
    this.#accessToken = response.credential;
  }
}
