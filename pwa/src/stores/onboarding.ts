import { defineStore } from 'pinia'

type ProviderStateType = {

}

type StateType = {
  email: null | string
  code: null | string
  providers: ProviderStateType[]
}

export const useOnBoardingStore = defineStore({
  id: 'onboarding',
  state: () => ({
    email: null,
    code: null,
    providers: []
  } as StateType),

  actions: {
    /**
     * Function to handle save e-mail user.
     * @param {string} payload 
     */
   async handleSaveEmail(payload: string) {
    this.email = payload
   }
  }
})