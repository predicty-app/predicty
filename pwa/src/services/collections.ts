import { hFirstAndLastAdsetDate } from "@/helpers/utils";
import type {
  AmountNumberType,
  CampaignType,
  AdSetsType,
  AdsType
} from "@/stores/userDashboard";

export type AdsStatType = {
  date: string;
  id: string;
  amountSpent: AmountNumberType;
  results: number;
  costPerResult: AmountNumberType;
};

export type AdNonParsedType = {
  id: string;
  name: string;
  externalId: string;
  campaignId: string;
  adStats: AdsStatType[];
};

export type CollectionNonParsedType = {
  id: string;
  name: string;
  ads: AdNonParsedType[];
};

/**
 * CollectionService
 * Collection service for parse collections.
 */
class CollectionService {
  /**
   * Function to parse campaigns list.
   * @param {CampaignNonParsedType[]} campaigns
   */
  public parseCollectionList(
    collections: CollectionNonParsedType[],
    campaigns: CampaignType[]
  ): CampaignType[] {
    if (collections.length === 0) {
      return campaigns;
    }

    const uniqueId = Math.round(
      Math.random() * (99999 - 10000) + 10000
    ).toString();
    const provider = this.#setProvidersList(collections, campaigns);

    campaigns.unshift({
      uid: uniqueId,
      isCollection: true,
      externalId: `external-id-${uniqueId}`,
      adsets: this.#setAdSetsList(collections, provider),
      name: "Collections list",
      color: `#${Math.floor(Math.random() * 16777215).toString(16)}`,
      dataProvider: provider
    } as CampaignType);

    campaigns = campaigns.map((campaign: CampaignType) => {
      campaign.adsets = campaign.adsets
        .filter((adset: AdSetsType) => adset.ads.length > 0)
        .map((adset: AdSetsType) => {
          const { first, last } = hFirstAndLastAdsetDate(adset.ads);
          adset.start = first;
          adset.end = last;

          adset.isActive = this.#checkIsActiveElement(first, last);
          return adset;
        });

      return campaign;
    });
    return campaigns;
  }

  /**
   * Function to set adsets.
   * @param {CampaignNonParsedType} campaign
   * @param {string[]} provider
   * @returns {AdSetsType[]}
   */
  #setAdSetsList(
    collections: CollectionNonParsedType[],
    provider: string[]
  ): AdSetsType[] {
    return collections
      .filter(
        (collection: CollectionNonParsedType) => collection.ads.length > 0
      )
      .map((collection: CollectionNonParsedType) => {
        collection.ads = collection.ads.filter(
          (ad: AdNonParsedType) => ad.adStats.length > 0
        );

        return {
          uid: collection.id,
          name: collection.name,
          externalId: `adset-external-id-${collection.id}`,
          start: "",
          campaignId: this.#checkCollectionWithinCampaign(collection.ads),
          isActive: false,
          end: "",
          ads: this.#setAdsList(collection.ads, provider)
        };
      });
  }

  /**
   * Function to set ads list.
   * @param {AdsNonParsedType[]} ads
   * @param {string[]} provider
   * @returns {AdsType[]}
   */
  #setAdsList(ads: AdNonParsedType[], provider: string[]): AdsType[] {
    return ads.map(
      (ad: AdNonParsedType) =>
        ({
          uid: ad.id,
          creation: "",
          name: ad.name,
          status: ad.adStats,
          end: ad.adStats.at(0).date,
          start: ad.adStats.at(-1).date,
          dataProvider: provider,
          isActive: this.#checkIsActiveElement(
            ad.adStats.at(-1).date,
            ad.adStats.at(0).date
          )
        } as AdsType)
    );
  }

  /**
   * Function to check is collection in one campaign.
   * @param {AdNonParsedType[]} ads
   * @returns
   */
  #checkCollectionWithinCampaign(ads: AdNonParsedType[]): string | null {
    let campaignId = ads[0].campaignId;

    ads.forEach((ad: AdNonParsedType) => {
      if (campaignId !== ad.campaignId) {
        campaignId = null;
        return;
      }
    });

    return campaignId;
  }

  /**
   * Function to check is active element.
   * @param {string} startDate
   * @param {string} endDate
   * @returns {boolean}
   */
  #checkIsActiveElement(startDate: string, endDate: string): boolean {
    const currentDate = new Date();

    const minDate = new Date(startDate);
    const maxDate = new Date(endDate);

    return currentDate > minDate && currentDate < maxDate;
  }

  /**
   * Function to set providers list.
   * @param {CollectionNonParsedType[]} collections
   * @param {CampaignType[]} campaigns
   * @returns {string[]}
   */
  #setProvidersList(
    collections: CollectionNonParsedType[],
    campaigns: CampaignType[]
  ): string[] {
    let providers = [];
    collections.forEach((collection: CollectionNonParsedType) => {
      collection.ads.forEach((ad: AdNonParsedType) => {
        providers = [
          ...new Set([
            ...providers,
            ...campaigns.find(
              (campaign: CampaignType) => campaign.uid === ad.campaignId
            ).dataProvider
          ])
        ];
      });
    });

    return providers;
  }
}

export default new CollectionService();
