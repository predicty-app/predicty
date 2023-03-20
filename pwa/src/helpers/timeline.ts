import { computed } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { CampaignType, AdSetsType } from "@/stores/userDashboard";

const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();

enum timelineParams {
  GAP_GRID = 5,
  LINE_WIDTH = 150,
  COLUMN_WIDTH = 16.4,
  GRADIENT_WIDTH = 300,
  FIREST_COLUMN_WIDTH = 16
}

export const scaleLines = computed<string>(
  () => `${timelineParams.LINE_WIDTH * (globalStore.currentScale * 0.01)}px`
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

export const heightContent = computed<string>(() => {
  let height = 0;
  userDashboardStore.campaigns.forEach((campaign: CampaignType) => {
    height += calculateItemHeight(campaign);
  });
  return `${height + 50}px`;
});

/**
 * Function to calculate height of box item campain.
 * @param {CampaignType} campaign
 * @return {number}
 */
export function calculateItemHeight(campaign: CampaignType): number {
  const adsets = campaign.adsets.filter(
    (adset: AdSetsType) => !adset.campaignId
  );
  let height = 0;

  adsets.forEach((adset: AdSetsType) => {
    height += adset.ads.length * 36 + adset.ads.length * 5;
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
  const previousHeightElement = 0;
  const index = userDashboardStore.campaigns.findIndex(
    (item: CampaignType) => item.uid === campaign.uid
  );

  for (let i = 0; i < index; i++) {
    let previousHeightElement = 0;
    for (let i = 0; i < index; i++) {
      userDashboardStore.campaigns[0].adsets.forEach((adset: AdSetsType) => {
        previousHeightElement +=
          adset.ads.length * 36 + adset.ads.length * 5 + modifierHeight;
      });
    }
  }

  return previousHeightElement;
}
