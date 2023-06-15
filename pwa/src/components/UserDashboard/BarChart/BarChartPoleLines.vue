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
  type DataProviderType
} from "@/stores/userDashboard";
import type {
  CampaignType,
  AdSetsType,
  AdsType,
  AdStatusType
} from "@/stores/userDashboard";
import * as d3Shape from 'd3-shape'
import * as d3Selection from 'd3-selection'

const globalStore = useGlobalStore();
const investmentWeekNumber = ref<number[]>([]);
const userDashboardStore = useUserDashboardStore();
const investmentNumber = ref<number[]>([]);
const instanceLines = ref<SVGElement | null>(null);

const investment = computed<number[]>(() => userDashboardStore.typeChart === TypeOptionsChart.WEEKS ? investmentWeekNumber.value : investmentNumber.value);
const scale = computed<number[]>(() => userDashboardStore.typeChart === TypeOptionsChart.DAYS ? scaleCharDaystLines.value : scaleCharWeekstLines.value);

onMounted(async () => {
  await calculateAll();
  drawLine();
  drawPointer();
});

watch(
  () => [
    userDashboardStore.activeProviders.length,
    userDashboardStore.hiddenAds.length,
    userDashboardStore.typeChart
  ],
  async () => {
    await calculateAll();
  }
);

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

function points():number[][] {
  let points = []
  points.push([scale.value * 0 - scale.value / 2, scale.value * 0]);

  for (let i = 0; i < investment.value.length; i++) {
    points.push([calculateLinePosition('x1', i), calculateLinePosition('y1', i, (investment.value[i] === 0 ? 0.1 : investment.value[i])* 100)]);
  };

  return points
}

function drawLine() {
  const lineGenerator = d3Shape.line().curve(d3Shape.curveCatmullRom.alpha(0.5));
  const pathData = lineGenerator(points());
  d3Selection.select('#line').attr('d', pathData);
}

function drawPointer() {
  const svg = d3Selection.select('svg');
  const allPoints = points();
  const pointer = d3Selection.select('#pointer');
  const minPoint = allPoints[0][0];
  const maxPoint = allPoints[allPoints.length - 1][0];

  pointer.append('rect').attr('width', 2).attr('x',-1).attr('height', '100%').attr('fill', '#D2D0D7');
  pointer.append('circle').attr('r', 8).attr("stroke", "#fff").attr('fill', '#6E7DD9').attr('stroke-width', 4);

  svg.on("mouseover", function(mouse) {
    pointer.style('display', 'block');
  });

  svg.on("mousemove", function(mouse) {
    const [x, y] = d3Selection.pointer(mouse);
    const ratio = x / svg.node().getBBox().width;
    const currentPoint = minPoint + Math.round(ratio * (maxPoint - minPoint));
    let closest = allPoints.reduce((prev, curr) => (Math.abs(curr[0] - currentPoint) < Math.abs(prev[0] - currentPoint) ? curr : prev));
    pointer.select('circle').attr('cx', closest[0]).attr('cy', closest[1]);
    pointer.select('rect').attr('x', closest[0] - 1);
  });

  svg.on("mouseout", function(mouse) {
    pointer.style('display', 'none');
  });
}
</script>

<template>
  <template
    v-if="
      userDashboardStore.scaleChart > 0 &&
      (investmentNumber.length > 0 || investmentWeekNumber.length > 0)
    "
  >
    <!-- <svg
      ref="instanceLines"
      class="absolute top-0 left-0 z-[100] w-full h-full scale-x-[1] scale-y-[-1]"
    >
      <line
        class="animate-fade-in"
        :x1="calculateLinePosition('x1', index)"
        :y1="calculateLinePosition('y1', index, investment * 100)"
        :x2="calculateLinePosition('x2', index)"
        :y2="calculateLinePosition('y2', index)"
        :key="`${investment[index]}_${index}`"
        style="stroke: #ffae4f; stroke-width: 2"
        v-for="(investment, index) in userDashboardStore.typeChart ===
        TypeOptionsChart.WEEKS
          ? investmentWeekNumber
          : investmentNumber"
      />
    </svg> -->
    <svg class="absolute top-0 left-0 z-[100] w-full h-full scale-x-[1] scale-y-[-1]">
      <path id="line" fill="none" stroke="#ffae4f" stroke-width="2"></path>
      <g id="pointer" style="display: none;"></g>
    </svg>
  </template>
</template>