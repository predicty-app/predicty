import {
  hWeeksBetween,
  hFirstDayWeek,
  hFirstDaysWeeks,
  hFirstAndLastDate,
  hNumberWeekFromDate,
  hNextDaysDictionary,
} from "@/helpers/utils";
import { ref } from 'vue'
import { useGlobalStore } from "@/stores/global";
import type { CampaignType, AdsType, AdsCollection } from '@/stores/userDashboard'

const list = ref<any>([])

type ManagementCollectionPayloadType = {
  campaignUid: string
  ads: string[]
  collectionUid: string
}

/**
 * Function to get all campaigns user.
 */
async function handleGetCampaigns() {
  list.value = [
    {
      uid: "23852953378710492",
      name: "9K | V3 | ATC",
      ads: [
        {
          uid: "23852953378790492",
          name: "Static | REM | Certyfikat - Luty",
          start: "2023-08-14",
          end: "2023-08-14",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
        {
          uid: "23852953378890495",
          name: "Static | Kreatywnosc 10",
          start: "2023-01-08",
          end: "2023-03-03",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
        {
          uid: "23852953378790497",
          name: "9K | Książka UX ręka 0zł",
          start: "2023-02-15",
          end: "2023-05-03",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
      ],
      collection: [
        {
          uid: "23852952378790497",
          name: "Collection 1",
          start: "2023-01-08",
          end: "2023-05-03",
          ads: ["23852953378790497", "23852953378890495"],
        },
      ],
    },
    {
      uid: "23852953378710495",
      name: "9K | V3 | ATC",
      ads: [
        {
          uid: "23852953378290492",
          name: "Static | REM | Certyfikat - Luty",
          start: "2023-01-01",
          end: "2023-03-03",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
        {
          uid: "23852953373790495",
          name: "Static | Kreatywnosc 100",
          start: "2023-01-08",
          end: "2023-03-03",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
        {
          uid: "23852953378790498",
          name: "9K | Książka UX ręka 0zł",
          start: "2023-02-08",
          end: "2023-05-03",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
      ],
      collection: [],
    },
    {
      uid: "23852953378710499",
      name: "9K | V3 | ATC",
      ads: [
        {
          uid: "22852953378790492",
          name: "Static | REM | Certyfikat - Luty",
          start: "2023-01-15",
          end: "2023-03-03",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
        {
          uid: "23852953478790495",
          name: "Static | Kreatywnosc 1000",
          start: "2023-01-08",
          end: "2023-04-03",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
        {
          uid: "23252953378790497",
          name: "9K | Książka UX ręka 0zł",
          start: "2023-02-08",
          end: "2023-05-03",
          creation: null,
          cost_total: 3496.68,
          cost_per_day: 40.191724137931,
        },
      ],
      collection: [],
    },
  ];

  const globalStore = useGlobalStore();

  const { first, last } = hFirstAndLastDate(list.value);

  globalStore.setCurrentWeeks(hWeeksBetween(first, last) + 2);
  globalStore.setFirstWeeks(hNumberWeekFromDate(first));

  globalStore.setDictionaryTimeline(
    hNextDaysDictionary(hFirstDayWeek(first), hWeeksBetween(first, last))
  );
  globalStore.setDictionaryDaysWeeks(
    hFirstDaysWeeks(hFirstDayWeek(first), hWeeksBetween(first, last))
  );

  return list.value;
}

/**
 * Function to create new collection.
 * @param {ManagementCollectionPayloadType} payload 
 */
function handleCreateCollection(payload: Pick<ManagementCollectionPayloadType, "ads" | "campaignUid">) {
  list.value = list.value.map((campaign: CampaignType) => {
    if(campaign.uid === payload.campaignUid) {
      const ads = campaign.ads.filter((ad: AdsType) => payload.ads.includes(ad.uid))
      
      const { first, last } = hFirstAndLastDate([{ ads } as CampaignType]);

      campaign.collection.push({
        uid: `${Math.random()}`,
        name: `Collection ${campaign.collection.length + 1}`,
        ads: payload.ads,
        start: first,
        end: last
      })
    }

    return campaign;
  })
}

/**
 * Function to assign ad to existing collection.
 * @param {ManagementCollectionPayloadType} payload 
 */
function handleAssignAdToCollection(payload: ManagementCollectionPayloadType) {
  list.value = list.value.map((campaign: CampaignType) => {
    if(campaign.uid === payload.campaignUid) {
      campaign.collection = campaign.collection.map((collection: AdsCollection) => {
        if(collection.uid === payload.collectionUid) {
          collection.ads = collection.ads.concat(payload.ads)

          const ads = campaign.ads.filter((ad: AdsType) => collection.ads.includes(ad.uid))


          const { first, last } = hFirstAndLastDate([{ ads } as CampaignType]);
          collection.start = first
          collection.end = last
        }

        return collection
      })
    }

    return campaign;
  })
}

export { handleGetCampaigns, handleCreateCollection, handleAssignAdToCollection };
