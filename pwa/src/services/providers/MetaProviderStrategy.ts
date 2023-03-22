/// <reference types="facebook-js-sdk" />

import fetchInject from "fetch-inject";
import type { StrategyProviders } from "@/services/providers/ContextProviders";

export default class MetaProviderStrategy implements StrategyProviders {
  /**
   * @var {string} #scopeAccessPrivilages
   * @private
   */
  #scopeAccessPrivilages = "ads_read, ads_management, read_insights";

  /**
   * @var {string} #accessToken
   * @private
   */
  #accessToken = "";

  /**
   * @var {string} #libraryPath
   * @private
   */
  #libraryPath = "https://connect.facebook.net/en_US/sdk.js";

  /**
   * Method to init current provider.
   */
  public async initProvider() {
    await fetchInject([this.#libraryPath]);
    window.FB.init({
      appId: import.meta.env.VITE_FB_ACCOUNT_APP,
      autoLogAppEvents: true,
      xfbml: true,
      version: "v16.0"
    });
  }

  /**
   * Method to send request permission to current provider.
   * @return {Promise<void>}
   */
  public async sendRequestPermission(): Promise<void> {
    return new Promise((resolve) => {
      window.FB.getLoginStatus((response: fb.StatusResponse) => {
        if (response.authResponse) {
          this.#setAccessToken(response);
          resolve();
        } else {
          window.FB.login(
            (response: fb.StatusResponse) => {
              this.#setAccessToken(response);
              resolve();
            },
            { scope: this.#scopeAccessPrivilages }
          );
        }
      });
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
   * @param {fb.StatusResponse} response
   */
  #setAccessToken(response: fb.StatusResponse) {
    if (response.authResponse) {
      this.#accessToken = response.authResponse.accessToken;
    }
  }
}
