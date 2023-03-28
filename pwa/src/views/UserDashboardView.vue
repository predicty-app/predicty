<script setup lang="ts">
import { reactive, computed, nextTick, ref } from "vue";
import { useI18n } from "vue-i18n";
import { useGlobalStore } from "@/stores/global";
import type { AdsType, CampaignType } from "@/stores/userDashboard";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { TypeOptionsChart, type AdSetsType } from "@/stores/userDashboard";
import {
  handleAssignAdToCollection,
  handleGetCampaigns
} from "@/services/api/userDashboard";

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

console.log(userDashboardStore.scaleChart);

const amountScale = computed<string[]>(() => [
  `$${
    userDashboardStore.scaleChart.toString() === "-Infinity"
      ? 0
      : userDashboardStore.scaleChart.toFixed(2)
  }`,
  `$${
    userDashboardStore.scaleChart.toString() === "-Infinity"
      ? 0
      : (userDashboardStore.scaleChart / 2).toFixed(2)
  }`,
  `$${
    userDashboardStore.scaleChart.toString() === "-Infinity"
      ? 0
      : (userDashboardStore.scaleChart / 3).toFixed(2)
  }`
]);

const isSpinnerVisible = ref<boolean>(false);
const isDropped = ref<boolean>(true);

/**
 * Function to check is ad in collection.
 * @param {AdsCollection[]} collections
 * @param {string} adUid
 * @return {boolean}
 */
function checkIsAdInCollection(adUid: string): boolean {
  return userDashboardStore.campaigns[0].isCollection &&
    userDashboardStore.campaigns[0].adsets.find((adsSet: AdSetsType) =>
      adsSet.ads.find((ad: AdsType) => ad.uid === adUid)
    )
    ? false
    : true;
}

/**
 * Function to toogle collection.
 * @param {AdsType | AdSetsType} value
 */
function toggleCollection(value?: AdSetsType) {
  userDashboardStore.selectedCollection = value ? value : null;
}

/**
 * Functions to drag and drop an ad into collection.
 */
function startDrag(e: any, ad: AdsType, campaign: CampaignType) {
  e.dataTransfer.dropEffect = "move";
  e.dataTransfer.effectAllowed = "move";
  userDashboardStore.isDragAndDrop = true;
  isDropped.value = false;
  userDashboardStore.toogleAssignAdsAction(campaign.uid, ad.uid, false);
}

function endDrag() {
  userDashboardStore.isDragAndDrop = false;
  userDashboardStore.selectedAdsList.ads = [];
  isDropped.value = true;
}

async function onDrop(e: any, collection: AdSetsType, campaign: CampaignType) {
  await handleAssignAdToCollection({
    campaignUid: campaign.uid,
    collectionUid: collection.uid,
    ads: userDashboardStore.selectedAdsList.ads
  });
  userDashboardStore.selectedAdsList.ads = [];
  userDashboardStore.selectedAdsList.campaignUid = null;
  await setResponseFiredAction();
}

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
  isDropped.value = true;
}
</script>

<template>
  <ZoomScale v-if="globalStore.isZoomActive" />
  <FloatingSwitchViewForm
    v-if="
      (userDashboardStore.selectedAdsList.ads.length > 0 ||
        userDashboardStore.selectedCollectionAdsList.ads.length > 0) &&
      !userDashboardStore.isDragAndDrop &&
      isDropped
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
      <BarChartWrapper />
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
          :key="campaign.uid"
          v-for="campaign in userDashboardStore.parsedCampaignsList"
        >
          <template v-if="campaign.isCollection && campaign.adsets.length > 0">
            <ChartTimelineItem
              :element="adset"
              type="collection"
              @drop="onDrop($event, adset, campaign)"
              @dragover.prevent
              @dragenter.prevent
              :is-visible="true"
              :uid="adset.uid"
              :color="campaign.color"
              :key="`${adset.uid}_${Math.random()}`"
              @collectionSelected="toggleCollection"
              v-for="adset in userDashboardStore.campaigns[0].adsets"
              :start="globalStore.dictionaryTimeline[adset.start]"
              :campaing-uid="campaign.uid"
              :end="globalStore.dictionaryTimeline[adset.end]"
            />
          </template>
          <template v-if="!campaign.isCollection">
            <template :key="adsSet.uid" v-for="adsSet in campaign.adsets">
              <ChartTimelineItem
                :element="ad"
                type="ad"
                draggable="true"
                @dragstart="startDrag($event, ad, campaign)"
                @dragend="endDrag"
                :is-visible="checkIsAdInCollection(ad.uid)"
                :uid="ad.uid"
                :color="campaign.color"
                :key="`${ad.uid}_${Math.random()}`"
                @collectionSelected="toggleCollection"
                v-for="ad in adsSet.ads"
                :start="globalStore.dictionaryTimeline[ad.start]"
                :campaing-uid="campaign.uid"
                :end="globalStore.dictionaryTimeline[ad.end]"
              />
            </template>
          </template>
        </ChartTimelineContent>
      </ChartTimelineWrapper>
    </template>
  </UserDashboardLayout>
  <CollectionBottomBar
    :collection="userDashboardStore.selectedCollection"
    @handleCloseDetials="toggleCollection()"
  />
</template>
