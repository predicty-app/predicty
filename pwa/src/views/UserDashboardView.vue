<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useGlobalStore } from "@/stores/global";
import { TypeOptionsChart, type AdSetsType } from "@/stores/userDashboard";
import type { AdsType } from "@/stores/userDashboard";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { reactive } from "vue";

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

let state = reactive({
  isCollectionSelected: false,
  currentCollection: null
});

/**
 * Function to check is ad in collection.
 * @param {AdsCollection[]} collections
 * @param {string} adUid
 * @return {boolean}
 */
function checkIsAdInCollection(adUid: string): boolean {
  return userDashboardStore.campaigns[0].adsets.find((adsSet: AdSetsType) =>
    adsSet.ads.find((ad: AdsType) => ad.uid === adUid)
  )
    ? false
    : true;
}

/**
 * Function to toogle collection.
 * @param {AdsType | AdSetsType} value
 */
function toggleCollection(value?: AdsType | AdSetsType) {
  state.isCollectionSelected = value ? true : false;
  state.currentCollection = value ? value : null;

  userDashboardStore.selectedCollection = value ? value : null;
}
</script>

<template>
  <FloatingSwitchViewForm
    v-if="userDashboardStore.selectedAdsList.ads.length > 0"
    :isCollection="userDashboardStore.selectedAdsList"
  />
  <UserDashboardLayout :singleRow="false">
    <template #header>
      <HeaderDashboard />
    </template>
    <template #chart-legend>
      <LegendDescription
        :options="legendOptions"
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
          <template v-if="campaign.isCollection">
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
    :collection="state.currentCollection"
    @close="toggleCollection()"
  />
</template>
