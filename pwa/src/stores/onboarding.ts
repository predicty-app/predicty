import { defineStore } from "pinia";

type ProviderStateType = {
  name: string;
  logoPath: string;
  status: boolean;
  token?: string;
};

type StateType = {
  email: null | string;
  password: null | string;
  providers: {};
};

export const useOnBoardingStore = defineStore({
  id: "onboarding",
  state: () =>
    ({
      email: null,
      password: null,
      providers: {},
    } as StateType),

  actions: {
    /**
     * Function to handle save e-mail user.
     * @param {string} payload
     */
    async handleSaveEmail(payload: string) {
      this.email = payload; 
    },

    /**
     * Function to handle save code(password) user.
     * @param {string} payload
     */
    async handleSavePassword(payload: string): Promise<boolean> {
      this.password = payload;
      return true;
    },

    /**
     * Function to handle save code(password) user.
     * @param {string} payload
     */
    async handleToogleAddProvider(payload: ProviderStateType) {
      if (payload.name in this.providers) {
        delete this.providers[payload.name];
      } else {
        this.providers[payload.name] = payload;
      }

      console.log(this.providers);
    },
  },
});
