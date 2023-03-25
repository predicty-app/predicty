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

      campaign.adsets.forEach((adsets: AdSetsType) => {
        adsets.ads.forEach((ad: AdsType) => {
          if (
            ad.uid === adSelected &&
            !userDashboardStore.hiddenAds.includes(ad.uid)
          ) {
            addingResults(ad.status);
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
    const ads = (userDashboardStore.selectedCollection as AdSetsType).ads.map(
      (ad: AdsType) => ad.uid
    );
    parseDateforAd(ads);
  }
}

/**
 * Function to set daily revenue;
 */
function setDailyRevenue() {
  dailyReveneuWeekNumber.value = 0;
  dailyReveneuNumber.value = [0, 0, 0, 0, 0, 0, 0];

  for (let i = 0; i < 7; i++) {
    userDashboardStore.dailyRevenue.forEach((daily: DailyRevenueType) => {
      const createdDate = parseCurrentDate(i);
      if (daily.date === createdDate) {
        if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
          dailyReveneuWeekNumber.value += daily.revenue.amount;
        } else {
          dailyReveneuNumber.value[i] = daily.revenue.amount;
        }
      }
    });
  }
}

/**
 * Function to count the investment amount.
 */
function setSpentInvestment() {
  investmentWeekNumber.value = 0;
  investmentNumber.value = [0, 0, 0, 0, 0, 0, 0];

  for (let i = 0; i < 7; i++) {
    userDashboardStore.campaigns
      .filter((campaign: CampaignType) =>
        userDashboardStore.activeProviders.includes(campaign.dataProvider[0])
      )
      .forEach((campaign: CampaignType) => {
        campaign.adsets.forEach((adset: AdSetsType) => {
          adset.ads
            .filter(
              (ad: AdsType) => !userDashboardStore.hiddenAds.includes(ad.uid)
            )
            .forEach((ad: AdsType) => {
              ad.status.forEach((adStat: AdStatusType) => {
                const createdDate = parseCurrentDate(i);
                if (adStat.date === createdDate) {
                  if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
                    investmentWeekNumber.value += adStat.amountSpent.amount;
                  } else {
                    investmentNumber.value[i] = adStat.amountSpent.amount;
                  }
                }
              });
            });
        });
      });
  }
}
</script>

<template>
  <div
    v-if="isElementVisible"
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
        :investment="investmentNumber[item - 1] * getScale()"
        :result="resultNumber[item - 1] * getScale()"
        v-for="item in 7"
      />
    </template>
    <template v-else>
      <BarChartPoleItem
        :type="TypeOptionsChart.WEEKS"
        :investment="investmentWeekNumber * getScale()"
        :height="dailyReveneuWeekNumber * getScale()"
        :key="`${Math.random()}_${props.fisrtDayWeek}`"
        :result="resultWeekNumber * getScale()"
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
