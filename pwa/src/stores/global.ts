import { defineStore } from "pinia";

type StateType = {
  isSpinnerVisible: boolean
};


export const useGlobalStore = defineStore({
  id: "global",
  state: () => ({
    isSpinnerVisible: false,
  } as StateType),

  actions: {
    /**
     * Function to change spinner state.
     */
    toogleSpinnerState() {
      this.isSpinnerVisible = !this.isSpinnerVisible;
    },
  },
});
