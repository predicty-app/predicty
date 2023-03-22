unction to handle scale down.
<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { ref, onMounted, watch } from "vue";
import {
  useUserDashboardStore,
  type AdSetsType,
  type AdsType,
  type CampaignType,
  type DailyRevenueType
} from "@/stores/userDashboard";
import {
  gapGrid,
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

const scaleChart = 6000;
const globalStore = useGlobalStore();
const resultNumber = ref<number[]>([0, 0, 0, 0, 0, 0, 0]);
const isElementVisible = ref<boolean>(true);
const userDashboardStore = useUserDashboardStore();
const boundingBoxElement = ref<DOMRect | null>(null);
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

watch(() => userDashboardStore.dailyRevenue, () => {
  setDailyRevenue();
})

watch(() => userDashboardStore.campaigns, () => {
  setDailyRevenue();
})

watch(() => userDashboardStore.hiddenAds, () => {
  concatResultsPerDay();
})

function setScale(): number {
  return globalStore.wrapperPole.getBoundingClientRect().height / scaleChart;
}

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
          if (ad.uid === adSelected && !userDashboardStore.hiddenAds.includes(ad.uid)) {
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
    const createdDate = parseCurrentDate(i)

    status.forEach((stat: AdStatusType) => {
      if (stat.date === createdDate) {
        resultNumber.value[i] += stat.revenueShare.amount;
      }
    });
  }
}

function parseCurrentDate(index: number): string {
  const date = props.fisrtDayWeek.split(".");
  const parsedDate = new Date(`${date[2]}-${date[1]}-${date[0]}`);
  parsedDate.setDate(parsedDate.getDate() + index);

  return `${parsedDate.getFullYear()}-${parsedDate.getMonth() + 1 < 10
      ? `0${parsedDate.getMonth() + 1}`
      : parsedDate.getMonth() + 1
    }-${parsedDate.getDate() < 10
      ? `0${parsedDate.getDate()}`
      : parsedDate.getDate()
    }`;
}

/**
 * Function to concat results.
 */
function concatResultsPerDay() {
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
  for (let i = 0; i < 7; i++) {
    userDashboardStore.dailyRevenue.forEach((daily: DailyRevenueType) => {
      const createdDate = parseCurrentDate(i);
      if (daily.date === createdDate) {
        dailyReveneuNumber.value[i] = daily.revenue.amount;
      }
    })
  }
}
</script>

<template>
  <div ref="barChartPoleContentInstance" class="bar-chart-pole-content w-full">
    <BarChartPoleItem :height="dailyReveneuNumber[item - 1] * setScale()" :key="`${Math.random()}_${item}`"
      :result="resultNumber[item - 1] * setScale()" v-for="item in 7" />
  </div>
</template>

<style scoped lang="scss">
.bar-chart-pole-content {
  display: grid;
  grid-template-columns: v-bind(scaleFirstGrid) repeat(auto-fill,
      v-bind(scaleGrid));
  grid-column-gap: v-bind(gapGrid);
}
</style>
