unction to handle scale down.
<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { ref, onMounted, watch } from "vue";
import {
  TypeOptionsChart,
  useUserDashboardStore,
  type AdSetsType,
  type AdsType,
  type CampaignType,
  type DailyRevenueType
} from "@/stores/userDashboard";
import {
  gapGrid,
  getScale,
  scaleGrid,
  scaleFirstGrid,
  handleVirtualizationElement
} from "@/helpers/timeline";
import type { AdStatusType } from "@/stores/userDashboard";

type PropsType = {
  fisrtDayWeek: string;
};

const props = withDefaults(defineProps<PropsType>(), {
  fisrtDayWeek: ""
});

const globalStore = useGlobalStore();
const resultWeekNumber = ref<number>(0);
const isElementVisible = ref<boolean>(true);
const investmentWeekNumber = ref<number>(0);
const dailyReveneuWeekNumber = ref<number>(0);
const userDashboardStore = useUserDashboardStore();
const boundingBoxElement = ref<DOMRect | null>(null);
const resultNumber = ref<number[]>([0, 0, 0, 0, 0, 0, 0]);
const investmentNumber = ref<number[]>([0, 0, 0, 0, 0, 0, 0]);
const dailyReveneuNumber = ref<number[]>([0, 0, 0, 0, 0, 0, 0]);
const barChartPoleContentInstance = ref<HTMLDivElement | null>(null);

onMounted(() => {
  boundingBoxElement.value =
    barChartPoleContentInstance.value.getBoundingClientRect();
  isElementVisible.value = handleVirtualizationElement(
    boundingBoxElement.value
  );
});

watch(
  () => [
    globalStore.scrollParams,
    globalStore.scrollTimeline,
    globalStore.currentScale
  ],
  () =>
    (isElementVisible.value = handleVirtualizationElement(
      boundingBoxElement.value
    ))
);

watch(isElementVisible, () => {
  concatResultsPerDay();
});

watch(
  () => userDashboardStore.selectedAdsList.ads.length,
  () => {
    concatResultsPerDay();
  }
);

watch(
  () => userDashboardStore.selectedCollection,
  () => {
    concatResultsPerDay();
  }
);

watch(
  () => userDashboardStore.dailyRevenue,
  () => {
    setDailyRevenue();
  }
);

watch(
  () => userDashboardStore.campaigns,
  () => {
    setDailyRevenue();
    setSpentInvestment();
  }
);

watch(
  () => userDashboardStore.hiddenAds,
  () => {
    concatResultsPerDay();
    setSpentInvestment();
  }
);

watch(
  () => userDashboardStore.typeChart,
  () => {
    concatResultsPerDay();
    setDailyRevenue();
    setSpentInvestment();
  }
);

/**
 * Function to parse ads.
 * @param {string[]} ads
 */
function parseDateforAd(ads: string[]) {
  ads.forEach((adSelected: string) => {
    userDashboardStore.campaigns.forEach((campaign: CampaignType) => {
      if (campaign.isCollection) {
        return;
      }

      campaign.adSets.forEach((adsets: AdSetsType) => {
        adsets.ads.forEach((ad: AdsType) => {
          if (
            ad.id === adSelected &&
            !userDashboardStore.hiddenAds.includes(ad.id)
          ) {
            addingResults(ad.adStats);
          }
        });
      });
    });
  });
}

/**
 * Function to adding results.
 * @param {AdStatusType[]}
 */
function addingResults(status: AdStatusType[]) {
  for (let i = 0; i < 7; i++) {
    const createdDate = parseCurrentDate(i);

    status.forEach((stat: AdStatusType) => {
      if (stat.date === createdDate) {
        if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
          resultWeekNumber.value += stat.revenueShare.amount;
        } else {
          resultNumber.value[i] += stat.revenueShare.amount;
        }
      }
    });
  }
}

function parseCurrentDate(index: number): string {
  const date = props.fisrtDayWeek.split(".");
  const parsedDate = new Date(`${date[2]}-${date[1]}-${date[0]}`);
  parsedDate.setDate(parsedDate.getDate() + index);

  return `${parsedDate.getFullYear()}-${
    parsedDate.getMonth() + 1 < 10
      ? `0${parsedDate.getMonth() + 1}`
      : parsedDate.getMonth() + 1
  }-${
    parsedDate.getDate() < 10
      ? `0${parsedDate.getDate()}`
      : parsedDate.getDate()
  }`;
}

function getNameOfDay(date: string): string {
  const days = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday"
  ];
  const d = new Date(date);
  return days[d.getDay()];
}

/**
 * Function to concat results.
 */
function concatResultsPerDay() {
  resultWeekNumber.value = 0;
  resultNumber.value = [0, 0, 0, 0, 0, 0, 0];
  if (userDashboardStore.selectedAdsList.ads.length > 0) {
    parseDateforAd(userDashboardStore.selectedAdsList.ads);
  }

  if (userDashboardStore.selectedCollection) {
    const ads = (
      userDashboardStore.selectedCollection.collection as AdSetsType
    ).ads.map((ad: AdsType) => ad.id);
    parseDateforAd(ads);
  }
}

/**
 * Function to set daily revenue;
 */
function setDailyRevenue() {
  dailyReveneuWeekNumber.value = 0;
  dailyReveneuNumber.value = [0, 0, 0, 0, 0, 0, 0];

  userDashboardStore.dailyRevenue.forEach((daily: DailyRevenueType) => {
    for (let i = 0; i < 7; i++) {
      const createdDate = parseCurrentDate(i);
      if (daily.date === createdDate) {
        if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
          dailyReveneuWeekNumber.value += daily.revenue.amount;
        } else {
          dailyReveneuNumber.value[i] = daily.revenue.amount;
        }
      }
    }
  });
  // console.log(dailyReveneuNumber.value)
}

function concatAllAdSetsToOne(): AdsType[] {
  return userDashboardStore.campaigns
    .filter((campaign: CampaignType) => !campaign.isCollection)
    .map((campaign: CampaignType) => campaign.adSets)
    .flat(1)
    .map((adset: AdSetsType) => adset.ads)
    .flat(1)
    .filter((ad: AdsType) => !userDashboardStore.hiddenAds.includes(ad.id));
}

/**
 * Function to count the investment amount.
 */
function setSpentInvestment() {
  investmentWeekNumber.value = 0;
  investmentNumber.value = [0, 0, 0, 0, 0, 0, 0];

  const adsList = concatAllAdSetsToOne();
  adsList.forEach((ad: AdsType) => {
    ad.adStats.forEach((adStat: AdStatusType) => {
      for (let i = 0; i < 7; i++) {
        const createdDate = parseCurrentDate(i);
        if (adStat.date === createdDate) {
          if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
            investmentWeekNumber.value += adStat.amountSpent.amount;
          } else {
            investmentNumber.value[i] += adStat.amountSpent.amount;
          }
        }
      }
    });
  });
}
</script>

<template>
  <div
    ref="barChartPoleContentInstance"
    :class="[
      'bar-chart-pole-content w-full',
      {
        'bar-chart-pole-content--weeks':
          userDashboardStore.typeChart === TypeOptionsChart.WEEKS
      }
    ]"
  >
    <template v-if="userDashboardStore.typeChart === TypeOptionsChart.DAYS">
      <BarChartPoleItem
        :height="dailyReveneuNumber[item - 1] * getScale()"
        :key="`${Math.random()}_${item}`"
        :investment="investmentNumber[item - 1]"
        :sales="dailyReveneuNumber[item - 1]"
        :date="parseCurrentDate(item - 1)"
        :day="getNameOfDay(parseCurrentDate(item - 1))"
        :result="resultNumber[item - 1] * getScale()"
        :id="`${Math.floor(Math.random() * 1000)}_${item}`"
        :class="dailyReveneuNumber[item - 1] * getScale()"
        v-for="item in 7"
      />
    </template>
    <template v-else>
      <BarChartPoleItem
        :type="TypeOptionsChart.WEEKS"
        :investment="investmentWeekNumber"
        :height="dailyReveneuWeekNumber * getScale()"
        :sales="dailyReveneuWeekNumber"
        :key="`${Math.random()}_${props.fisrtDayWeek}`"
        :result="resultWeekNumber * getScale()"
        :id="`${Math.floor(Math.random() * 1000)}_${item}`"
      />
    </template>
  </div>
</template>

<style scoped lang="scss">
.bar-chart-pole-content {
  display: grid;
  grid-template-columns: v-bind(scaleFirstGrid) repeat(
      auto-fill,
      v-bind(scaleGrid)
    );
  grid-column-gap: v-bind(gapGrid);

  &--weeks {
    grid-template-columns: 1fr;
  }
}
</style>
