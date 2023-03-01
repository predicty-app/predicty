import { defineStore } from "pinia";

export enum ViewNames {
  "AD" = "ad",
  "AD_COLLECTION" = "ad_collection",
}

export enum MenuNames {
  "SETTINGS" = "settings",
  "LOGOUT" = "logout",
}

type StateType = {
  currentView: ViewNames;
};

export const useUserDashboardStore = defineStore({
  id: "userDashboard",
  state: () =>
    ({
      currentView: "ad",
    } as StateType),

  actions: {
    handleChangeView(viewName: ViewNames) {
      this.currentView = viewName;
    },
  },
});
