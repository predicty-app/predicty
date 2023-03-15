import { defineStore } from "pinia";

export enum FilesTypes {
  META = "FACEBOOK_CSV",
  GOOGLE = "GOOGLE_CSV",
  OOH = "OOH_CSV",
  SALES_DATA = "SALES_DATA_CSV",
  OTHER = "OTHER"
}

type ProviderStateType = {
  name: string;
  logoPath: string;
  status: boolean;
  token?: string;
};

type FileType = {
  file: File | null;
  type: FilesTypes;
  name: string;
};

type StateType = {
  email: null | string;
  file: FileType;
  password: null | string;
  providers: {};
};

export const useOnBoardingStore = defineStore({
  id: "onboarding",
  state: () =>
    ({
      email: null,
      file: {
        file: null,
        type: null,
        name: ""
      },
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
     * Function to handle save file with data.
     * @param {File} file
     */
    async handleSaveFile(file: File) {
      this.file.file = file;
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
