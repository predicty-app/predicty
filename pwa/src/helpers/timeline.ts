import { computed } from "vue";
import { useGlobalStore } from "@/stores/global";
import { hCheckIsCollectionExist } from "@/helpers/utils";
import type { CampaignType, AdSetsType } from "@/stores/userDashboard";
import {
  useUserDashboardStore,
  TypeOptionsChart
} from "@/stores/userDashboard";

const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();

enum timelineParams {
  GAP_GRID = 5,
  LINE_WIDTH = 150,
  COLUMN_WIDTH = 16.4,
  LINES_POSITION_DAYS_WIDTH = 21,
  LINES_POSITION_WEEKS_WIDTH = 150,
  GRADIENT_WIDTH = 300,
  SCALE_CHART = 50000,
  FIREST_COLUMN_WIDTH = 16
}

export const scaleLines = computed<string>(
  () => `${timelineParams.LINE_WIDTH * (globalStore.currentScale * 0.01)}px`
);

export const scaleCharDaystLines = computed<number>(
  () =>
    timelineParams.LINES_POSITION_DAYS_WIDTH * (globalStore.currentScale * 0.01)
);

export const scaleCharWeekstLines = computed<number>(
  () =>
    timelineParams.LINES_POSITION_WEEKS_WIDTH *
    (globalStore.currentScale * 0.01)
);

export const scaleLinesGradient = computed<string>(
  () => `${timelineParams.GRADIENT_WIDTH * (globalStore.currentScale * 0.01)}px`
);

export const mainWidthGrid = computed<string>(
  () =>
    `${
      globalStore.currentsCountWeeks *
      (timelineParams.LINE_WIDTH * (globalStore.currentScale * 0.01))
    }px`
);

export const scaleGrid = computed<string>(
  () => `${timelineParams.COLUMN_WIDTH * (globalStore.currentScale * 0.01)}px`
);

export const scaleFirstGrid = computed<string>(
  () =>
    `${
      timelineParams.FIREST_COLUMN_WIDTH * (globalStore.currentScale * 0.01)
    }px`
);

export const gapGrid = computed<string>(
  () => `${timelineParams.GAP_GRID * (globalStore.currentScale * 0.01)}px`
);

export const heightCollectionContent = computed<string>(() => {
  let height = 0;
  if (!userDashboardStore.selectedCollection) {
    return `0px`;
  }

  (userDashboardStore.selectedCollection as AdSetsType).ads.forEach(() => {
    height += 57;
  });

  return `${height + 10}px`;
});

export const heightContent = computed<string>(() => {
  let height = 0;
  if (userDashboardStore.campaigns.length === 0) {
    return `0px`;
  }
  hCheckIsCollectionExist(userDashboardStore.campaigns).forEach(
    (campaign: CampaignType) => {
      height += calculateItemHeight(campaign, 10);
    }
  );

  return `${height + 100}px`;
});

/**
 * Function to calculate height of box item campain.
 * @param {CampaignType} campaign
 * @return {number}
 */
export function calculateItemHeight(
  campaign: CampaignType,
  modifierHeight: number = 0
): number {
  let height = 0;

  campaign.adsets.forEach((adset: AdSetsType) => {
    height += campaign.isCollection
      ? 100
      : adset.ads.length * 36 + adset.ads.length * 5 + modifierHeight;
  });

  return height;
}

/**
 * Function to virtualize elements.
 * @param {DOMRect} boundingBoxElement
 * @returns {boolean}
 */
export function handleVirtualizationElement(
  boundingBoxElement: DOMRect
): boolean {
  if (!globalStore.scrollTimeline) {
    return;
  }
  const currentLeftPosition =
    (boundingBoxElement.left -
      globalStore.scrollTimeline.getBoundingClientRect().left) *
    (globalStore.currentScale * 0.01);
  const widthElement =
    boundingBoxElement.width * (globalStore.currentScale * 0.01);

  return (
    currentLeftPosition <
      globalStore.scrollParams.x +
        globalStore.scrollTimeline.getBoundingClientRect().width &&
    currentLeftPosition + widthElement > globalStore.scrollParams.x
  );
}

/**
 * Function to calculate item position.
 * @param {CampaignType} campaign
 * @param {number} modifierHeight
 * @return {number}
 */
export function calculateItemPosition(
  campaign: CampaignType,
  modifierHeight: number
): number {
  let previousHeightElement = 0;
  const campaigns = hCheckIsCollectionExist(userDashboardStore.campaigns);
  const index = campaigns.findIndex(
    (item: CampaignType) => item.uid === campaign.uid
  );

  for (let i = 0; i < index; i++) {
    campaigns[i].adsets.forEach((adset: AdSetsType) => {
      previousHeightElement += campaigns[i].isCollection
        ? 110 + 4
        : adset.ads.length * 36 + adset.ads.length * 5 + modifierHeight;
    });
  }

  return previousHeightElement;
}

/**
 * Function to change dynamical type of chart.
 */
export function changeDynamicalTypeChart() {
  if (globalStore.currentScale <= 80) {
    userDashboardStore.typeChart = TypeOptionsChart.WEEKS;
  } else {
    if (userDashboardStore.typeChart === TypeOptionsChart.WEEKS) {
      userDashboardStore.typeChart = TypeOptionsChart.DAYS;
    }
  }
}

/**
 * Function to get scale for bars.
 * @returns {number}
 */
export function getScale(): number {
  return (
    (globalStore.wrapperPole.parentNode as HTMLElement).getBoundingClientRect()
      .height / userDashboardStore.scaleChart
  );
}
