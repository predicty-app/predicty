import { defineStore } from "pinia";
import { useGlobalStore } from "@/stores/global";
import { hCheckIsCollectionExist } from "@/helpers/utils";

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

export type AmountNumberType = {
  currency: string;
  amount: number;
};

export type AdStatusType = {
  results: number;
  amountSpent: AmountNumberType;
  costPerResult: AmountNumberType;
  revenueShare: AmountNumberType;
  date: string;
  id: string;
};

export type AdsType = {
  uid: string;
  end: string;
  name: string;
  start: string;
  creation: string;
  isActive: boolean;
  status: AdStatusType[];
  dataProvider: string[];
};

export type AdSetsType = {
  uid: string;
  name: string;
  externalId: string;
  start: string;
  campaignId?: string;
  isActive: boolean;
  end: string;
  ads: AdsType[];
};

export type CampaignType = {
  uid: string;
  name: string;
  adsets: AdSetsType[];
  color?: string;
  isCollection?: boolean;
  campaignId?: string;
  externalId: string;
  dataProvider: string[];
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

export type DailyRevenueType = {
  id: string;
  revenue: AmountNumberType;
  averageOrderValue: AmountNumberType;
  date: string;
};

type StateType = {
  hiddenAds: string[];
  campaigns: CampaignType[];
  activeProviders: string[];
  dailyRevenue: DailyRevenueType[];
  selectedCollection: AdSetsType | AdsType;
  parsedCampaignsList: CampaignType[];
  selectedAdsList: CheckedAdsToCollectionType;
  authenticatedUserParams: AuthenticatedUserParamsType;
};

export const useUserDashboardStore = defineStore({
  id: "userDashboard",
  state: () =>
    ({
      dailyRevenue: [],
      authenticatedUserParams: null,
      campaigns: [],
      parsedCampaignsList: [],
      hiddenAds: [],
      activeProviders: [
        "GOOGLE_ADS",
        "FACEBOOK_ADS",
        "GOOGLE_ANALYTICS",
        "TIK_TOK",
        "OTHER"
      ],
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
      this.campaigns = list;
    },

    /**
     * Function to set daily reveneu list to store.
     * @param {DailyRevenueType[]} list
     */
    setDailyReveneu(list: DailyRevenueType[]) {
      this.dailyRevenue = list;
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
      this.parsedCampaignsList = hCheckIsCollectionExist(this.campaigns).filter(
        (campaign: CampaignType, index: number) => {
          let currentHeightElement = 0;

          campaign.adsets.forEach((adset: AdSetsType) => {
            currentHeightElement += campaign.isCollection
              ? 51
              : adset.ads.length * 36 + adset.ads.length * 5 + 10;
          });

          let previousHeightElement = 0;
          for (let i = 0; i < index; i++) {
            this.campaigns[i].adsets.forEach((adset: AdSetsType) => {
              previousHeightElement += this.campaigns[i].isCollection
                ? 51
                : adset.ads.length * 36 + adset.ads.length * 5 + 12;
            });
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
