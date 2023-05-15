import { defineStore } from "pinia";
import { useGlobalStore } from "@/stores/global";
import { hCheckIsCollectionExist } from "@/helpers/utils";

export enum OptionsName {
  "CREATE_NEW_COLLECTION" = "create_new_collection",
  "ADD_TO_COLLECTION" = "add_to_collection",
  "HIDE_ELEMENT" = "hide_element",
  "REMOVE_FROM_COLLECTION" = "remove_from_collection"
}

export enum TypeOptionsChart {
  "WEEKS" = "weeks",
  "DAYS" = "days"
}

export enum MenuNames {
  "SETTINGS" = "settings",
  "FILES" = "files",
  "IMPORTS" = "imports",
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

export type CollectionType = {
  id: string;
  ads: AdsType[];
  name: string;
  startAt?: string;
  endAt?: string;
  dataProvider?: DataProviderType | string[];
};

export type AdsType = {
  id: string;
  externalId: string;
  name: string;
  campaignId?: string;
  adStats: AdStatusType[];
  dataProvider?: DataProviderType | string[];
};

export type AdSetsType = {
  id: string;
  ads: AdsType[];
  name: string;
  endedAt: string;
  externalId: string;
  startedAt?: string;
  dataProvider?: DataProviderType | string[];
};

export type DataProviderType = {
  id: string;
};

export type CampaignType = {
  id: string;
  externalId: string;
  dataProvider: DataProviderType | string[];
  name: string;
  adSets: AdSetsType[];
  color?: string;
  isCollection?: boolean;
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
  isOnboardingComplete: boolean;
};

export type DailyRevenueType = {
  id: string;
  revenue: AmountNumberType;
  averageOrderValue: AmountNumberType;
  date: string;
};

export type ColorType = {
  hex: string;
};

export type CommentType = {
  createdAt: string;
  comment: string;
};

export type ConversationsType = {
  id: string;
  date: string;
  color: ColorType;
  comments: CommentType[];
};

export type SelectedCollectionType = {
  color: string;
  collection: AdSetsType;
};

type StateType = {
  scaleChart: number;
  hiddenAds: string[];
  isDragAndDrop: boolean;
  draggedAd: string;
  campaigns: CampaignType[];
  activeProviders: string[];
  typeChart: TypeOptionsChart;
  dailyRevenue: DailyRevenueType[];
  conversations: ConversationsType[];
  parsedCampaignsList: CampaignType[];
  selectedCollection: SelectedCollectionType;
  selectedAdsList: CheckedAdsToCollectionType;
  authenticatedUserParams: AuthenticatedUserParamsType;
  selectedCollectionAdsList: Pick<CheckedAdsToCollectionType, "ads">;
};

export const useUserDashboardStore = defineStore({
  id: "userDashboard",
  state: () =>
    ({
      scaleChart: 0,
      typeChart: TypeOptionsChart.DAYS,
      dailyRevenue: [],
      conversations: [],
      authenticatedUserParams: null,
      campaigns: [],
      parsedCampaignsList: [],
      hiddenAds: [],
      isDragAndDrop: false,
      draggedAd: null,
      selectedCollection: null,
      activeProviders: [
        "GOOGLE_ADS",
        "FACEBOOK_ADS",
        "GOOGLE_ANALYTICS",
        "TIK_TOK",
        "OTHER"
      ],
      selectedCollectionAdsList: {
        ads: []
      },
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
     * Function to set conversations list to store.
     * @param {ConversationsType[]} list
     */
    setConversationsList(list: ConversationsType[]) {
      this.conversations = list;
    },

    /**
     * Function to set user params.
     * @param {AuthenticatedUserParamsType} user
     */
    setAuthenticatedUserParams(user: AuthenticatedUserParamsType) {
      this.authenticatedUserParams = user;
    },

    /**
     * Function to toogle select ads in collection.
     * @param {string} adUid
     */
    toogleAssignAdsCollectionAction(adUid: string) {
      if (this.selectedCollectionAdsList.ads.includes(adUid)) {
        this.selectedCollectionAdsList.ads =
          this.selectedCollectionAdsList.ads.filter(
            (item: string) => item !== adUid
          );
      } else {
        this.selectedCollectionAdsList.ads.push(adUid);
      }
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
        // ...
      } else {
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
     * Function to change state of ads visibility
     * @param {string[]} adsList
     */
    removeVisibilityAds(adsList: string[]) {
      this.hiddenAds = this.hiddenAds.filter((x) => !adsList.includes(x));
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

          campaign.adSets.forEach((adset: AdSetsType) => {
            currentHeightElement += campaign.isCollection
              ? 110
              : adset.ads.length * 36 + adset.ads.length * 5 + 10;
          });

          let previousHeightElement = 0;
          for (let i = 0; i < index; i++) {
            this.campaigns[i].adSets.forEach((adset: AdSetsType) => {
              previousHeightElement += this.campaigns[i].isCollection
                ? 110
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
