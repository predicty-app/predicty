import { defineStore } from 'pinia'

export const useGlobalStore = defineStore({
  id: 'global',
  state: () => ({
    isSpinnerVisible: false
  }),

  actions: {
    /**
     * Function to change spinner state.
     */
    toogleSpinnerState() {
      this.isSpinnerVisible = !this.isSpinnerVisible
    }
  }
})