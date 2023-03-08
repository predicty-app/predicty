import { defineStore } from "pinia";

export enum OptionsName {
  "CREATE_NEW_COLLECTION" = "create_new_collection",
  "ADD_TO_COLLECTION" = "add_to_collection",
  "HIDE_ELEMENT" = "hide_element",
}

export enum TypeOptionsChart {
  "WEEKS" = "weeks",
  "DAYS" = "days",
}

export enum MenuNames {
  "SETTINGS" = "settings",
  "LOGOUT" = "logout",
}

export type AdsType = {
  uid: string;
  name: string;
  start: string;
  end: string;
  creation: string;
  cost_total: number;
  cost_per_day: number;
};

export type AdsCollection = {
  uid: string;
  name: string;
  ads: string[];
  start: string;
  end: string;
};

export type CampaignType = {
  uid: string;
  name: string;
  ads: AdsType[];
  color?: string;
  collection: AdsCollection[];
};

type CheckedAdsToCollectionType = {
  campaignUid: string;
  ads: string[];
};

type StateType = {
  selectedAdsList: CheckedAdsToCollectionType;
  campaigns: CampaignType[];
};

export const useUserDashboardStore = defineStore({
  id: "userDashboard",
  state: () =>
    ({
      campaigns: [],
      selectedAdsList: {
        campaignUid: null,
        ads: [],
      },
    } as StateType),

  actions: {
    /**
     * Function to set campaigns list to store.
     * @param {CampaignType[]} list
     */
    setCampaignsList(list: CampaignType[]) {
      this.campaigns = list.map((campaign: CampaignType) => {
        campaign.color = `#${Math.floor(Math.random() * 16777215).toString(
          16
        )}`;
        return campaign;
      });
    },

    /**
     * Function to assign ad to list action.
     * @param {string} campaignUid
     * @param {string} adUid
     */
    toogleAssignAdsAction(campaignUid: string, adUid: string) {
      if (this.selectedAdsList.ads.includes(adUid)) {
        this.selectedAdsList.ads = this.selectedAdsList.ads.filter(
          (item: string) => item !== adUid
        );
      } else {
        this.selectedAdsList.ads.push(adUid);
      }

      if (this.selectedAdsList.ads.length === 0) {
        this.selectedAdsList.campaignUid = null;
      } else {
        this.selectedAdsList.campaignUid = campaignUid;
      }
    },
  },
});
