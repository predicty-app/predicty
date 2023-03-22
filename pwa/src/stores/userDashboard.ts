import { defineStore } from "pinia";
import { useGlobalStore } from "@/stores/global";

export enum OptionsName {
  "CREATE_NEW_COLLECTION" = "create_new_collection",
  "ADD_TO_COLLECTION" = "add_to_collection",
  "HIDE_ELEMENT" = "hide_element"
}

export enum TypeOptionsChart {
  "WEEKS" = "weeks",
  "DAYS" = "days"
}

export enum MenuNames {
  "SETTINGS" = "settings",
  "LOGOUT" = "logout"
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
  isCollection?: boolean;
};

type AuthenticatedUserParamsType = {
  uid: string;
  email: string;
  isEmailVerified: boolean;
};

type StateType = {
  authenticatedUserParams: AuthenticatedUserParamsType;
  selectedAdsList: CheckedAdsToCollectionType;
  campaigns: CampaignType[];
  hiddenAds: string[];
  activeProviders: string[];
  parsedCampaignsList: CampaignType[];
};

export const useUserDashboardStore = defineStore({
  id: "userDashboard",
  state: () =>
    ({
      authenticatedUserParams: null,
      campaigns: [],
      parsedCampaignsList: [],
      hiddenAds: [],
      activeProviders: ["google-analytics", "google-ads", "meta"],
      selectedAdsList: {
        campaignUid: null,
        ads: []
      }
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
     * Function to set user params.
     * @param {AuthenticatedUserParamsType} user
     */
    setAuthenticatedUserParams(user: AuthenticatedUserParamsType) {
      this.authenticatedUserParams = user;
    },

    /**
     * Function to assign ad to list action.
     * @param {string} campaignUid
     * @param {string} adUid
     */
    toogleAssignAdsAction(
      campaignUid: string,
      adUid: string,
      isCollection?: boolean
    ) {
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
        this.selectedAdsList.isCollection = isCollection || false;
      }
    },

    /**
     * Function to change state of ads visibility
     * @param {string[]} adsList
     */
    toogleVisibilityAds(adsList: string[]) {
      this.hiddenAds = adsList
        .filter((x) => !this.hiddenAds.includes(x))
        .concat(this.hiddenAds.filter((x) => !adsList.includes(x)));
    },

    /**
     * Function to set visible providers.
     * @param {string[]} providers
     */
    handleSetVisibleProviders(providers: string[]) {
      this.activeProviders = providers;
    },

    /**
     * Function to virtualize campaign list.
     */
    handleVirtualizeCampaignsList() {
      const globalStore = useGlobalStore();

      this.parsedCampaignsList = this.campaigns.filter(
        (campaign: CampaignType, index: number) => {
          const currentHeightElement =
            (campaign.ads.length + campaign.collection.length) * 36 +
            (campaign.ads.length + campaign.collection.length) * 5;

          let previousHeightElement = 0;
          for (let i = 0; i < index; i++) {
            previousHeightElement +=
              (this.campaigns[i].ads.length +
                this.campaigns[i].collection.length) *
                36 +
              (this.campaigns[i].ads.length +
                this.campaigns[i].collection.length) *
                5;
          }

          const currentTopPosition = previousHeightElement;
          return (
            currentTopPosition <
              globalStore.scrollCampaignList.scrollTop +
                globalStore.scrollCampaignList.getBoundingClientRect().height &&
            currentTopPosition + currentHeightElement >
              globalStore.scrollCampaignList.scrollTop
          );
        }
      );
    }
  }
});
