import {
  hRandomColor,
  hWeeksBetween,
  hFirstDayWeek,
  hFirstDaysWeeks,
  hFirstAndLastDate,
  hNumberWeekFromDate,
  hNextDaysDictionary,
  hLightenDarkenColor
} from "@/helpers/utils";
import { useGlobalStore } from "@/stores/global";
import type {
  AmountNumberType,
  CampaignType,
  AdsType,
  AdSetsType
} from "@/stores/userDashboard";

export type AdsStatType = {
  date: string;
  id: string;
  amountSpent: AmountNumberType;
  results: number;
  costPerResult: AmountNumberType;
};

export type AdsNonParsedType = {
  id: string;
  name: string;
  externalId: string;
  adStats: AdsStatType[];
};

export type AdSetsNonParsedType = {
  id: string;
  ads: AdsNonParsedType[];
  name: string;
  startedAt: string;
  endedAt: string;
  externalId: string;
};

export type DataProviderType = {
  id: string;
};

export type CampaignNonParsedType = {
  id: string;
  name: string;
  externalId: string;
  adSets: AdSetsNonParsedType[];
  dataProvider: DataProviderType;
};

/**
 * CampaignsService
 * Campaigns service for parse campaigns.
 */
class CampaignsService {
  /**
   * Function to parse campaigns list.
   * @param {CampaignNonParsedType[]} campaigns
   */
  public async parseCampaignsList(
    campaigns: CampaignNonParsedType[]
  ): Promise<CampaignType[]> {
    return new Promise((resolve) => {
      const parsedList: CampaignType[] = campaigns
        .map(
          (campaign: CampaignNonParsedType) =>
            ({
              uid: campaign.id,
              name: campaign.name,
              externalId: campaign.externalId,
              adsets: this.#setAdSetsList(campaign),
              dataProvider: [campaign.dataProvider.id],
              color: hLightenDarkenColor(hRandomColor(), -50)
            } as CampaignType)
        )
        .filter((campaign: CampaignType) => campaign.adsets.length > 0);

      this.#prepareTimelineParams(parsedList);
      resolve(parsedList);
    });
  }

  /**
   * Function to set adsets.
   * @param {CampaignNonParsedType} campaign
   * @returns {AdSetsType[]}
   */
  #setAdSetsList(campaign: CampaignNonParsedType): AdSetsType[] {
    return campaign.adSets
      .filter((adset: AdSetsNonParsedType) => adset.startedAt)
      .map((adset: AdSetsNonParsedType) => {
        adset.ads = adset.ads.filter(
          (ad: AdsNonParsedType) => ad.adStats.length > 0
        );

        return adset;
      })
      .filter((adset: AdSetsNonParsedType) => adset.ads.length > 0)
      .map(
        (adset: AdSetsNonParsedType) =>
          ({
            uid: adset.id,
            name: adset.name,
            end: this.#getStartEndAtDate(adset.ads, 0),
            start: this.#getStartEndAtDate(adset.ads, -1),
            externalId: adset.externalId,
            ads: this.#setAdsList(adset.ads, campaign),
            isActive: this.#checkIsActiveElement(
              this.#getStartEndAtDate(adset.ads, -1),
              this.#getStartEndAtDate(adset.ads, 0)
            )
          } as AdSetsType)
      );
  }

  /**
   * Method to get start and end for adset.
   * @param {AdsNonParsedType[]} ads
   * @param {number} type
   * @returns {string}
   */
  #getStartEndAtDate(ads: AdsNonParsedType[], type: number): string {
    const dates = [];

    ads
      .filter((ad: AdsNonParsedType) => ad.adStats.length >= 1)
      .forEach((ad: AdsNonParsedType) => {
        dates.push(
          parseInt(
            ad.adStats.at(type).date.replace("-", "").replace("-", ""),
            10
          )
        );
      });

    const min = Math[type === -1 ? "min" : "max"](...dates).toString();
    return `${min.slice(0, 4)}-${min.slice(4, 6)}-${min.slice(6, 8)}`;
  }

  /**
   * Function to set ads list.
   * @param {AdsNonParsedType[]} ads
   * @returns {AdsType[]}
   */
  #setAdsList(
    ads: AdsNonParsedType[],
    campaign: CampaignNonParsedType
  ): AdsType[] {
    return ads
      .filter((ad: AdsNonParsedType) => ad.adStats.length >= 1)
      .map(
        (ad: AdsNonParsedType) =>
          ({
            uid: ad.id,
            creation: "",
            name: ad.name,
            status: ad.adStats,
            end: ad.adStats.at(0).date,
            start: ad.adStats.at(-1).date,
            dataProvider: [campaign.dataProvider.id],
            isActive: this.#checkIsActiveElement(
              ad.adStats.at(-1).date,
              ad.adStats.at(0).date
            )
          } as AdsType)
      );
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
   * Function to prepare timeline params.
   * @param {CampaignType[]} campaigns
   */
  #prepareTimelineParams(campaigns: CampaignType[]) {
    const globalStore = useGlobalStore();

    const { first, last } = hFirstAndLastDate(campaigns);
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
}

export default new CampaignsService();
