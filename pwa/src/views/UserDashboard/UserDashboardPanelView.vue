<script setup lang="ts">
import { computed, nextTick, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useGlobalStore } from "@/stores/global";
import type { AdsType, CampaignType } from "@/stores/userDashboard";
import {
  type DataProviderType,
  useUserDashboardStore
} from "@/stores/userDashboard";
import {
  TypeOptionsChart,
  type AdSetsType,
  type SelectedCollectionType
} from "@/stores/userDashboard";
import {
  handleAssignAdToCollection,
  handleGetCampaigns
} from "@/services/api/userDashboard";
import { hGetStartEndAtDate } from "@/helpers/utils";

const { t } = useI18n();

type TypesOptionsChart = {
  label: string;
  key: string;
};

type OptionsLegendType = {
  label: string;
  color: string;
};

const legendOptions: OptionsLegendType[] = [
  {
    label: t("views.user-dashboard-view.legend-description.options.sales"),
    color: "#4184FF"
  },
  {
    label: t("views.user-dashboard-view.legend-description.options.investment"),
    color: "#FFAE4F"
  }
];

const chartTypeOptions: TypesOptionsChart[] = [
  {
    key: TypeOptionsChart.WEEKS,
    label: t("views.user-dashboard-view.legend-description.chart-types.weeks")
  },
  {
    key: TypeOptionsChart.DAYS,
    label: t("views.user-dashboard-view.legend-description.chart-types.days")
  }
];

const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();
const isZoomVisible = ref<boolean>(false);

const amountScale = computed<string[]>(() => [
  `$ ${
    userDashboardStore.scaleChart.toString() === "-Infinity"
      ? 0
      : userDashboardStore.scaleChart.toFixed(2)
  }`,
  `$ ${
    userDashboardStore.scaleChart.toString() === "-Infinity"
      ? 0
      : (userDashboardStore.scaleChart / 2).toFixed(2)
  }`,
  `$ ${
    userDashboardStore.scaleChart.toString() === "-Infinity"
      ? 0
      : (userDashboardStore.scaleChart / 4).toFixed(2)
  }`
]);

const isSpinnerVisible = ref<boolean>(false);

/**
 * Function to check is ad in collection.
 * @param {AdsCollection[]} collections
 * @param {string} adUid
 * @return {boolean}
 */
function checkIsAdInCollection(adUid: string): boolean {
  return userDashboardStore.campaigns[0].isCollection &&
    userDashboardStore.campaigns[0].adSets.find((adsSet: AdSetsType) =>
      adsSet.ads.find((ad: AdsType) => ad.id === adUid)
    )
    ? false
    : true;
}

/**
 * Function to toggle collection.
 * @param {AdsType | AdSetsType} value
 * @param {string} color
 */
function toggleCollection(collection: SelectedCollectionType) {
  userDashboardStore.selectedCollection = collection ? collection : null;
}

/**
 * Function to drag an ad into collection.
 * @param {DragEvent} e
 * @param {AdsType} as
 */
function handleStartDrag(e: DragEvent, ad: AdsType) {
  e.dataTransfer.dropEffect = "move";
  e.dataTransfer.effectAllowed = "move";

  if (userDashboardStore.selectedAdsList.ads.length > 1) {
    let ghost = (e.currentTarget as Document).cloneNode(true) as HTMLDivElement;

    ghost.classList.add("border-[2px]");
    ghost.style.position = "fixed";
    ghost.style.borderColor = (
      (e.currentTarget as HTMLElement).querySelector(
        ".chart-timeline-item"
      ) as HTMLElement
    ).style.getPropertyValue("--color");
    document.body.appendChild(ghost);
    e.dataTransfer.setDragImage(ghost, 0, 0);
  }

  userDashboardStore.isDragAndDrop = true;
  userDashboardStore.draggedAd = ad.id;
}

/**
 * Function to handle end drag element.
 */
function handleEndDrag() {
  userDashboardStore.isDragAndDrop = false;
}

/**
 * Function to handle drop element on collection.
 * @param {AdSetsType} collection
 * @param {CampaignType} campaign
 */
async function handleDropElement(
  collection: AdSetsType,
  campaign: CampaignType
) {
  isSpinnerVisible.value = true;
  await handleAssignAdToCollection({
    campaignUid: campaign.id,
    collectionUid: collection.id,
    ads: [
      userDashboardStore.draggedAd,
      ...userDashboardStore.selectedAdsList.ads
    ]
  });
  userDashboardStore.draggedAd = null;
  userDashboardStore.selectedAdsList.ads = [];
  await setResponseFiredAction();
}

/**
 * Function to response fired action.
 */
async function setResponseFiredAction() {
  isSpinnerVisible.value = true;
  const { campaigns, dailyRevenue } = await handleGetCampaigns();
  userDashboardStore.setCampaignsList(campaigns);
  userDashboardStore.setDailyReveneu(dailyRevenue);
  nextTick(() => {
    userDashboardStore.handleVirtualizeCampaignsList();
    isSpinnerVisible.value = false;
  });
  isSpinnerVisible.value = false;
}
</script>

<template>
  <ZoomScale v-if="isZoomVisible" />
  <ConversationCommentsCreateForm />
  <FloatingSwitchViewForm
    v-if="
      (userDashboardStore.selectedAdsList.ads.length > 0 ||
        userDashboardStore.selectedCollectionAdsList.ads.length > 0) &&
      !userDashboardStore.isDragAndDrop
    "
  />
  <SpinnerBar :is-visible="isSpinnerVisible" :is-global="true" />
  <UserDashboardLayout>
    <template #header>
      <HeaderDashboard />
    </template>
    <template #chart-legend>
      <LegendDescription
        :options="legendOptions"
        :amount-scale="amountScale"
        :typeChartOptions="chartTypeOptions"
      />
    </template>
    <template #providers-list>
      <ProvidersListForm />
    </template>
    <template #chart-weeks>
      <BarChartWeeks />
    </template>
    <template #chart>
      <BarChartWrapper
        @mouseenter="isZoomVisible = true"
        @mouseleave="isZoomVisible = false"
      />
    </template>
    <template #chart-days>
      <BarChartDaysWeek />
    </template>
    <template #ads-campaigns>
      <CampaningListForm />
    </template>
    <template #ads-weeks>
      <ChartTimelineWeeks />
    </template>
    <template #ads-chart>
      <ChartTimelineWrapper>
        <ChartTimelineContent
          :campaign="campaign"
          :key="campaign.id"
          v-for="campaign in userDashboardStore.parsedCampaignsList"
        >
          <template v-if="campaign.isCollection && campaign.adSets.length > 0">
            <ChartTimelineItem
              :element="adSet"
              type="collection"
              @drop="handleDropElement(adSet, campaign)"
              @dragover.prevent
              @dragenter.prevent
              :is-visible="true"
              :uid="adSet.id"
              :color="campaign.color"
              :key="`${adSet.id}_${Math.random()}`"
              @collectionSelected="toggleCollection"
              v-for="adSet in userDashboardStore.campaigns[0].adSets"
              :start="
                globalStore.dictionaryTimeline[hGetStartEndAtDate(adSet.ads, 0)]
              "
              :campaing-uid="campaign.id"
              :end="
                globalStore.dictionaryTimeline[
                  hGetStartEndAtDate(adSet.ads, -1)
                ]
              "
            />
          </template>
          <template v-if="!campaign.isCollection">
            <template :key="adsSet.uid" v-for="adsSet in campaign.adSets">
              <ChartTimelineItem
                :element="ad"
                type="ad"
                draggable="true"
                @dragstart="handleStartDrag($event, ad)"
                @dragend="handleEndDrag"
                :is-visible="checkIsAdInCollection(ad.id)"
                :uid="ad.id"
                :data-provider="(campaign.dataProvider as DataProviderType).id"
                :color="campaign.color"
                :key="`${ad.id}_${Math.random()}`"
                @collectionSelected="toggleCollection"
                v-for="ad in adsSet.ads"
                :start="globalStore.dictionaryTimeline[ad.adStats.at(0).date]"
                :campaing-uid="campaign.id"
                :end="globalStore.dictionaryTimeline[ad.adStats.at(-1).date]"
              />
            </template>
          </template>
        </ChartTimelineContent>
      </ChartTimelineWrapper>
    </template>
  </UserDashboardLayout>
  <CollectionBottomBar
    :selected-collection="userDashboardStore.selectedCollection"
    @handleCloseDetails="toggleCollection(null)"
  />
</template>
