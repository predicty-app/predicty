<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { useGlobalStore } from "@/stores/global";
import {
  getScale,
  scaleCharDaystLines,
  scaleCharWeekstLines
} from "@/helpers/timeline";
import {
  useUserDashboardStore,
  TypeOptionsChart,
  type DailyRevenueType
} from "@/stores/userDashboard";
import type {
  CampaignType,
  AdSetsType,
  AdsType,
  AdStatusType
} from "@/stores/userDashboard";

const globalStore = useGlobalStore();
const investmentWeekNumber = ref<number[]>([]);
const userDashboardStore = useUserDashboardStore();
const investmentNumber = ref<number[]>([]);
const instanceLines = ref<SVGElement | null>(null);

onMounted(async () => {
  await calcualteAll();
});

watch(
  () => [
    userDashboardStore.activeProviders.length,
    userDashboardStore.hiddenAds.length,
    userDashboardStore.typeChart
  ],
  async () => {
    await calcualteAll();
  }
);

/**
 * Functon to fill array of investments.
 */
async function insertInvestmentArray() {
  investmentNumber.value = [];
  investmentWeekNumber.value = [];
  return new Promise((resolve) => {
    for (let i = 0; i < globalStore.dictionaryFirstDaysWeek.length * 7; i++) {
      investmentNumber.value.push(0);
    }

    for (let i = 0; i < globalStore.dictionaryFirstDaysWeek.length; i++) {
      investmentWeekNumber.value.push(0);
    }
    resolve(true);
  });
}

/**
 * Function calculate height of bars.
 */
async function calcualteAll() {
  await insertInvestmentArray();
  await setSpentInvestment();

  const dailyRevenue = userDashboardStore.dailyRevenue.map(
    (current: DailyRevenueType) => current.revenue.amount
  );

  const divider =
    userDashboardStore.typeChart === TypeOptionsChart.WEEKS ? 9 : 1;
  const modifier =
    userDashboardStore.typeChart === TypeOptionsChart.WEEKS
      ? Math.max(...dailyRevenue) * divider
      : Math.max(...dailyRevenue) * divider;

  userDashboardStore.scaleChart = Math.max(...dailyRevenue) + modifier;
  setHeightLinesSvgElement();
}

/**
 * Function calculate height lines svg.
 */
function setHeightLinesSvgElement() {
  if (instanceLines.value) {
    instanceLines.value.style.height = `${
      (
        globalStore.wrapperPole.parentNode as HTMLElement
      ).getBoundingClientRect().height
    }px`;
  }
}

/**
 * Function to parse day week.
 * @param {string} firstDayWeek
 * @param {index} index
 * @return {string}
 */
function parseCurrentDate(firstDayWeek: string, index: number): string {
  const date = firstDayWeek.split(".");
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
 * Function to prepare list ads for parsed.
 * @return {AdsType[]}
 */
function concatAllAdSetsToOne(): AdsType[] {
  return userDashboardStore.campaigns
    .filter((campaign: CampaignType) => !campaign.isCollection)
    .map((campaign: CampaignType) => campaign.adsets)
    .flat(1)
    .map((adset: AdSetsType) => adset.ads)
    .flat(1)
    .filter((ad: AdsType) => !userDashboardStore.hiddenAds.includes(ad.uid));
}

/**
 * Function to set spent investments.
 */
async function setSpentInvestment() {
  const adsList = concatAllAdSetsToOne();

  globalStore.dictionaryFirstDaysWeek.forEach(
    (firstDayWeek: string, index: number) => {
      const currentIndex = index * 7;
      adsList.forEach((ad: AdsType) => {
        ad.status.forEach((adStat: AdStatusType) => {
          for (let i = 0; i < 7; i++) {
            const createdDate = parseCurrentDate(firstDayWeek, i);
            if (adStat.date === createdDate) {
              if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
                investmentWeekNumber.value[index] += adStat.amountSpent.amount;
              } else {
                investmentNumber.value[i + currentIndex] +=
                  adStat.amountSpent.amount;
              }
            }
          }
        });
      });
    }
  );
}

/**
 * Function to calculate line position.
 * @param {'x1' | 'y1' | 'x2' | 'y2'} type
 * @param {number} index
 * @param {number} value
 * @return {number}
 */
function calculateLinePosition(
  type: "x1" | "y1" | "x2" | "y2",
  index: number,
  value?: number
): number {
  const scale =
    userDashboardStore.typeChart === TypeOptionsChart.DAYS
      ? scaleCharDaystLines.value
      : scaleCharWeekstLines.value;

  switch (type) {
    case "x1": {
      return scale * index + scale / 2;
    }
    case "y1": {
      return value * getScale();
    }
    case "x2": {
      return scale * (index + 1) + scale / 2;
    }
    case "y2": {
      const nextValue =
        userDashboardStore.typeChart === TypeOptionsChart.DAYS
          ? investmentNumber.value[index + 1]
          : investmentWeekNumber.value[index + 1];

      return nextValue ? nextValue * getScale() : 0;
    }
  }
}
</script>

<template>
  <template
    v-if="
      userDashboardStore.scaleChart > 0 &&
      (investmentNumber.length > 0 || investmentWeekNumber.length > 0)
    "
  >
    <svg
      ref="instanceLines"
      class="absolute top-0 left-0 z-[100] w-full h-full scale-x-[1] scale-y-[-1]"
    >
      <line
        class="animate-fade-in"
        :x1="calculateLinePosition('x1', index)"
        :y1="calculateLinePosition('y1', index, investment)"
        :x2="calculateLinePosition('x2', index)"
        :y2="calculateLinePosition('y2', index)"
        :key="`${investment[index]}_${index}`"
        style="stroke: #ffae4f; stroke-width: 2"
        v-for="(investment, index) in userDashboardStore.typeChart ===
        TypeOptionsChart.WEEKS
          ? investmentWeekNumber
          : investmentNumber"
      />
    </svg>
  </template>
</template>
