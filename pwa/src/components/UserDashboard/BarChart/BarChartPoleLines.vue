<script setup lang="ts">
import { ref, onMounted, watch, computed } from "vue";
import { useGlobalStore } from "@/stores/global";
import {
  getScale,
  scaleCharDaystLines,
  scaleCharWeekstLines
} from "@/helpers/timeline";
import {
  useUserDashboardStore,
  TypeOptionsChart,
  type DailyRevenueType,
  type DataProviderType,
  type TooltipType
} from "@/stores/userDashboard";
import type {
  CampaignType,
  AdSetsType,
  AdsType,
  AdStatusType
} from "@/stores/userDashboard";
import * as d3Shape from "d3-shape";
import * as d3Selection from "d3-selection";
import { drawLine, drawPointer, clearLine, toggleBars } from "@/services/charts/draw";

const globalStore = useGlobalStore();
const investmentWeekNumber = ref<number[]>([]);
const userDashboardStore = useUserDashboardStore();
const investmentNumber = ref<number[]>([]);
const instanceLines = ref<SVGElement | null>(null);
const revenue = ref<number[]>([]);
const tooltipData = ref<TooltipType[]>([]);

const investment = computed<number[]>(() =>
  userDashboardStore.typeChart === TypeOptionsChart.WEEKS
    ? investmentWeekNumber.value
    : investmentNumber.value
);

const scale = computed<number>(() =>
  userDashboardStore.typeChart === TypeOptionsChart.DAYS
    ? scaleCharDaystLines.value
    : scaleCharWeekstLines.value
);

const emit = defineEmits<{
  (e: "togglePointer", value: boolean): void;
}>();

onMounted(async () => {
  await prepareVisuals();
});

watch(
  () => [
    userDashboardStore.activeProviders.length,
    userDashboardStore.hiddenAds.length,
    userDashboardStore.typeChart
  ],
  async () => {
    await prepareVisuals();
  }
);

watch(
  () => globalStore.currentScale,
  async () => {
    await prepareVisuals();
  }
);

watch(
  () => userDashboardStore.visualTypeChart,
  async () => {
    await prepareVisuals();
  }
);

async function prepareVisuals() {
  await calculateAll();
  drawLine('line', investment.value);
  if(userDashboardStore.visualTypeChart === 'bar') {
    toggleBars(true);
    clearLine('lineBar')
  } else {
    toggleBars(false);
    drawLine('lineBar', revenue.value);
  }
  drawPointer('lineSvg', 'pointer', 'tooltip', revenue.value, tooltipData.value);
}

/**
 * Function to fill array of investments.
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

function calculateRevenue() {
  let rev = []
  let data = []
  let sum = 0
  let counter = 0
  let copyRev = [...userDashboardStore.dailyRevenue]
  let dailyRev = copyRev.reverse()
  revenue.value = []

  globalStore.dictionaryFirstDaysWeek.map((date: string, i:number) => {
    dailyRev.map(item => {
      let fDate = date.split('.').reverse().join('-')
      let nDate = globalStore.dictionaryFirstDaysWeek[i + 1] || 0

      if(new Date(item.date).getTime() >= new Date(fDate).getTime() && new Date(item.date).getTime() < new Date(nDate.split('.').reverse().join('-')).getTime()) {
        if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
          sum += item.revenue.amount;
        } else {
          rev.push(item.revenue.amount)
          data.push({
            date: item.date,
            sales: item.revenue.amount,
            investment: investmentNumber.value[counter]
          })
        }
        counter++
      }
    })
    if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
      rev.push(sum)
      data.push({
        date: date,
        sales: sum,
        investment: investmentWeekNumber.value[i]
      })
      sum = 0
    }
  })
  revenue.value = rev.filter(Number)
  tooltipData.value = data
}

/**
 * Function calculate height of bars.
 */
async function calculateAll() {
  await insertInvestmentArray();
  await setSpentInvestment();

  let dailyRevenue = userDashboardStore.dailyRevenue.map(
    (current: DailyRevenueType) => current.revenue.amount
  );

  if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
    dailyRevenue = [];
    globalStore.dictionaryFirstDaysWeek.forEach(() => dailyRevenue.push(0));
    globalStore.dictionaryFirstDaysWeek.forEach(
      (firstDayWeek: string, index: number) => {
        for (let i = 0; i < 7; i++) {
          const createdDate = parseCurrentDate(firstDayWeek, i);

          userDashboardStore.dailyRevenue.forEach(
            (current: DailyRevenueType) => {
              if (createdDate === current.date) {
                dailyRevenue[index] += current.revenue.amount;
              }
            }
          );
        }
      }
    );
  }

  userDashboardStore.scaleChart = Math.max(...dailyRevenue);
  setHeightLinesSvgElement();
  calculateRevenue()
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
    .filter((campaign: CampaignType) =>
      userDashboardStore.activeProviders.includes(
        (campaign.dataProvider as DataProviderType).id
      )
    )
    .map((campaign: CampaignType) => campaign.adSets)
    .flat(1)
    .map((adSet: AdSetsType) => adSet.ads)
    .flat(1)
    .filter((ad: AdsType) => !userDashboardStore.hiddenAds.includes(ad.id));
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
        ad.adStats.forEach((adStat: AdStatusType) => {
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
      id="lineSvg"
    >
    <g>
      <path id="line" fill="none" stroke="#ffae4f" stroke-width="2"></path>
      <path id="lineBar" fill="none" stroke="#6E7DD9" stroke-width="2"></path>
    </g>
      <g id="pointer" style="display: none"></g>
    </svg>
    <div id="tooltip" class="absolute bg-basic-white drop-shadow-md rounded-xl z-[9999] fixed animate-fade-in text-center py-[10px] px-3 top-dynamic">
      <SalesNumber :sales="userDashboardStore.currentTooltip.sales" :investment="userDashboardStore.currentTooltip.investment" :date="userDashboardStore.currentTooltip.date" currency="$" />
    </div>
  </template>
</template>