import {
  hWeeksBetween,
  hFirstDayWeek,
  hFirstDaysWeeks,
  hFirstAndLastDate,
  hNumberWeekFromDate,
  hNextDaysDictionary,
} from "@/helpers/utils";
import { useGlobalStore } from "@/stores/global";

/**
 * Function to get all campaigns user.
 */
async function handleGetCampaigns() {
  const list = [
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

  const { first, last } = hFirstAndLastDate(list);

  globalStore.setCurrentWeeks(hWeeksBetween(first, last) + 2);
  globalStore.setFirstWeeks(hNumberWeekFromDate(first));

  globalStore.setDictionaryTimeline(
    hNextDaysDictionary(hFirstDayWeek(first), hWeeksBetween(first, last))
  );
  globalStore.setDictionaryDaysWeeks(
    hFirstDaysWeeks(hFirstDayWeek(first), hWeeksBetween(first, last))
  );

  return list;
}

export { handleGetCampaigns };
