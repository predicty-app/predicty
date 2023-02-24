/// <reference types="facebook-js-sdk" />

import fetchInject from 'fetch-inject'
import type { StrategyProviders } from '@/services/providers/ContextProviders'

export default class MetaProviderStrategy implements StrategyProviders {
  public async initProvider() {
    await fetchInject(['https://connect.facebook.net/en_US/sdk.js'])
    window.FB.init({
      appId            : import.meta.env.FB_ACCOUNT_APP,
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v16.0'
    });

    window.FB.getLoginStatus(function(response: fb.StatusResponse) {
      console.log(response)
    })

  }
}