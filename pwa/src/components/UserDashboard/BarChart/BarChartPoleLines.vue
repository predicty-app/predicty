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

onMounted(async () => {
  await calcualteAll();
});

async function insertInvestmentArray() {
  investmentNumber.value = [];
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

async function calcualteAll() {
  await insertInvestmentArray();
  setSpentInvestment();

  const dailyRevenue = userDashboardStore.dailyRevenue.map((current: DailyRevenueType) => current.revenue.amount);
  if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
    userDashboardStore.scaleChart = Math.max(...investmentWeekNumber.value);
  } else {
    userDashboardStore.scaleChart = Math.max(...investmentNumber.value);
  }
}

watch(
  () => userDashboardStore.activeProviders.length,
  async () => {
    await calcualteAll();
  }
);

watch(
  () => userDashboardStore.hiddenAds.length,
  async () => {
    await calcualteAll();
  }
);

watch(
  () => userDashboardStore.typeChart,
  async () => {
    await calcualteAll();
  }
);

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

async function setSpentInvestment() {
  globalStore.dictionaryFirstDaysWeek.forEach(
    (firstDayWeek: string, index: number) => {
      for (let i = 0; i < 7; i++) {
        userDashboardStore.campaigns
          .filter((campaign: CampaignType) =>
            userDashboardStore.activeProviders.includes(
              campaign.dataProvider[0]
            )
          )
          .forEach((campaign: CampaignType) => {
            campaign.adsets.forEach((adset: AdSetsType) => {
              adset.ads
                .filter(
                  (ad: AdsType) =>
                    !userDashboardStore.hiddenAds.includes(ad.uid)
                )
                .forEach((ad: AdsType) => {
                  ad.status.forEach((adStat: AdStatusType) => {
                    const createdDate = parseCurrentDate(firstDayWeek, i);
                    if (adStat.date === createdDate) {
                      if (
                        userDashboardStore.typeChart === TypeOptionsChart.WEEKS
                      ) {
                        investmentWeekNumber.value[index] +=
                          adStat.amountSpent.amount;
                      } else {
                        investmentNumber.value[i * (index + 1)] =
                          adStat.amountSpent.amount;
                      }
                    }
                  });
                });
            });
          });
      }
    }
  );
}

/**
 * Function to calculate line position.
 * @param {number} index
 * @return {number}
 */
function calculateLinePosition(index: number): number {
  if (userDashboardStore.typeChart === TypeOptionsChart.DAYS) {
    return index === 0
      ? scaleCharDaystLines.value / 2
      : scaleCharDaystLines.value * index +
          (6 * index - 1) +
          scaleCharDaystLines.value / 2;
  }
  if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
    return index === 0
      ? scaleCharWeekstLines.value / 2
      : scaleCharWeekstLines.value * index +
          (6 * index - 1) +
          scaleCharWeekstLines.value / 2;
  }
}
</script>

<template>
  <template v-if="userDashboardStore.scaleChart > 0">
    <template v-if="userDashboardStore.typeChart === TypeOptionsChart.WEEKS">
      <svg
        v-if="userDashboardStore.scaleChart > 0"
        class="absolute top-0 left-0 w-full h-full scale-x-[1] scale-y-[-1]"
      >
        <line
          class="animate-fade-in"
          :x1="calculateLinePosition(index)"
          :data-x-position="
            index - 1 === -1 ? 0 : investmentNumber[index - 1] * getScale()
          "
          :y1="
            index - 1 === -1 ? 0 : investmentWeekNumber[index - 1] * getScale()
          "
          :x2="calculateLinePosition(index + 1)"
          :y2="investment * getScale()"
          :key="`${investment[index]}_${index}`"
          style="stroke: #ffae4f; stroke-width: 2"
          v-for="(investment, index) in investmentWeekNumber"
        />
      </svg>
    </template>
    <template v-else>
      <svg
        v-if="userDashboardStore.scaleChart > 0"
        class="absolute top-0 left-0 w-full h-full scale-x-[1] scale-y-[-1]"
      >
        <line
          class="animate-fade-in"
          :x1="calculateLinePosition(index)"
          :data-x-position="calculateLinePosition(index)"
          :y1="index - 1 === -1 ? 0 : investmentNumber[index - 1] * getScale()"
          :x2="calculateLinePosition(index + 1)"
          :y2="investment * getScale()"
          :key="`${investment[index]}_${index}`"
          style="stroke: #ffae4f; stroke-width: 2"
          v-for="(investment, index) in investmentNumber"
        />
      </svg>
    </template>
  </template>
</template>
