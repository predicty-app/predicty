import {
  hRandomColor,
  hWeeksBetween,
  hFirstDayWeek,
  hFirstDaysWeeks,
  hNumberWeekFromDate,
  hNextDaysDictionary,
  hLightenDarkenColor,
  hGetFirstAndLastDate
} from "@/helpers/utils";
import { useGlobalStore } from "@/stores/global";
import type {
  AdsType,
  CampaignType,
  CollectionType,
  DataProviderType
} from "@/stores/userDashboard";

/**
 * CampaignsService
 * Campaigns service for parse campaigns.
 */
class CampaignsService {
  /**
   *
   * @param {CampaignNonParsedType[]} campaigns
   * @param {CollectionNonParsedType[]} collections
   * @returns {CampaignNonParsedType[]}
   */
  public createCollectionList(
    campaigns: CampaignType[],
    collections: CollectionType[]
  ): CampaignType[] {
    if (collections.length === 0) {
      this.#prepareTimelineParams(campaigns);
      return campaigns;
    }

    collections = collections.filter(
      (collection: CollectionType) => collection.ads.length > 0
    );
    const uniqueId = Math.round(
      Math.random() * (99999 - 10000) + 10000
    ).toString();

    const color = hLightenDarkenColor(hRandomColor(), -50);
    let dataProviders = [];

    collections = collections.map((collection: CollectionType) => {
      const dataProviderCollection = this.#setProvidersListCollection(
        collection.ads,
        campaigns
      );
      dataProviders.push(
        this.#setProvidersListCollection(collection.ads, campaigns)
      );

      collection.dataProvider = [
        ...new Set([].concat([], dataProviderCollection))
      ] as string[];
      return collection;
    });

    dataProviders = [...new Set([].concat([], dataProviders))];
    campaigns.unshift({
      id: uniqueId,
      name: "Collections list",
      externalId: `external-id-${uniqueId}`,
      adSets: collections,
      dataProvider: dataProviders,
      isCollection: true,
      color
    } as CampaignType);

    this.#prepareTimelineParams(campaigns);
    return campaigns.map((campaign: CampaignType) => {
      const color = hLightenDarkenColor(hRandomColor(), -50);
      campaign.color = color;
      return campaign;
    });
  }

  /**
   * Function to prepare timeline params.
   * @param {CampaignNonParsedType[]} campaigns
   */
  #prepareTimelineParams(campaigns: CampaignType[]) {
    const globalStore = useGlobalStore();

    const { first, last } = hGetFirstAndLastDate(campaigns);
    let weeksBetween = hWeeksBetween(first, last) + 2;

    weeksBetween = weeksBetween < 10 ? weeksBetween + 15 : weeksBetween;

    globalStore.setCurrentWeeks(weeksBetween);
    globalStore.setFirstWeeks(hNumberWeekFromDate(first));

    globalStore.setDictionaryTimeline(
      hNextDaysDictionary(hFirstDayWeek(first), weeksBetween)
    );

    globalStore.setDictionaryDaysWeeks(
      hFirstDaysWeeks(hFirstDayWeek(first), weeksBetween)
    );
  }

  /**
   * Function to set providers for collection.
   * @param {AdNonParsedType[]} ads
   * @param {CampaignType[]} campaigns
   * @returns {string[]}
   */
  #setProvidersListCollection(
    ads: AdsType[],
    campaigns: CampaignType[]
  ): string[] {
    const providers = [];
    ads.forEach((ad: AdsType) => {
      const currentCampaign = campaigns.find(
        (campaign: CampaignType) => campaign.id === ad.campaignId
      );

      const dataProviders = currentCampaign
        ? (currentCampaign.dataProvider as DataProviderType).id
        : "";
      providers.push(dataProviders);

      ad.dataProvider = [dataProviders];
    });

    return providers;
  }
}

export default new CampaignsService();
