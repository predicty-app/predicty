<script setup lang="ts">
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import { useGlobalStore } from "@/stores/global";
import type { AdsType } from "@/stores/userDashboard";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { TypeOptionsChart, type AdSetsType } from "@/stores/userDashboard";

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

console.log(userDashboardStore.scaleChart)

const amountScale = computed<string[]>(() => [
  `$${userDashboardStore.scaleChart.toString() === '-Infinity' ? 0 : userDashboardStore.scaleChart.toFixed(2)}`,
  `$${userDashboardStore.scaleChart.toString() === '-Infinity' ? 0 : (userDashboardStore.scaleChart / 2).toFixed(2)}`,
  `$${userDashboardStore.scaleChart.toString() === '-Infinity' ? 0 :  (userDashboardStore.scaleChart / 3).toFixed(2)}`
]);

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
</script>

<template>
  <ZoomScale v-if="globalStore.isZoomActive" />
  <FloatingSwitchViewForm
    v-if="
      userDashboardStore.selectedAdsList.ads.length > 0 ||
      userDashboardStore.selectedCollectionAdsList.ads.length > 0
    "
  />
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
                :is-visible="checkIsAdInCollection(ad.uid)"
                :uid="ad.uid"
                :color="campaign.color"
                :key="`${ad.uid}_${Math.random()}`"
                v-for="ad in adsSet.ads"
                @collectionSelected="toggleCollection"
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
