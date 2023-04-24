import { defineStore } from "pinia";

export enum AvalibleProviders {
  GOOGLE_ADS = "GOOGLE_ADS",
  FACEBOOK_ADS = "FACEBOOK_ADS",
  GOOGLE_ANALYTICS = "GOOGLE_ANALYTICS",
  TIK_TOK = "TIK_TOK",
  OTHER = "OTHER"
}

type ProviderStateType = {
  name: string;
  logoPath: string;
  status: boolean;
  token?: string;
};

export type FileType = {
  file: File | null;
  type: string;
  name: string;
  fileImportTypes: string;
};

type StateType = {
  email: null | string;
  file: FileType;
  password: null | string;
  moreServices: FileType[];
  providers: {};
};

export const useOnBoardingStore = defineStore({
  id: "onboarding",
  state: () =>
    ({
      email: null,
      moreServices: [],
      password: null,
      providers: {}
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
    async handleSavePassword(payload: string) {
      this.password = payload;
    },

    /**
     * Function to handle assign new file service.
     * @param {FileType} service
     */
    async handleSaveCustomService(service: FileType) {
      this.moreServices.push(service);
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
    }
  }
});
