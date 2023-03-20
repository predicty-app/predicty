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

function toggleCollection(value?: AdsType) {
  state.isCollectionSelected = value ? true : false;
  state.currentCollection = value ? value : null;
}
</script>

<template>
  <FloatingSwitchViewForm
    v-if="userDashboardStore.selectedAdsList.ads.length > 0"
  />
  <UserDashboardLayout>
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
          <ChartTimelineItem
            :element="adset"
            type="collection"
            :is-visible="true"
            :uid="adset.uid"
            :color="campaign.color"
            :key="`${adset.uid}_${Math.random()}`"
            v-for="adset in userDashboardStore.campaigns[0].adsets.filter((adsSet: AdSetsType) => adsSet.campaignId === campaign.uid)"
            :start="globalStore.dictionaryTimeline[adset.start]"
            :campaing-uid="campaign.uid"
            :end="globalStore.dictionaryTimeline[adset.end]"
          />
          <template
            :key="adsSet.uid"
            v-for="adsSet in campaign.adsets.filter((adsSet: AdSetsType) => !adsSet.campaignId)"
          >
            <ChartTimelineItem
              :element="ad"
              type="ad"
              :is-visible="checkIsAdInCollection(ad.uid)"
              :uid="ad.uid"
              :color="campaign.color"
              :key="`${ad.uid}_${Math.random()}`"
              v-for="ad in adsSet.ads"
              :start="globalStore.dictionaryTimeline[ad.start]"
              :campaing-uid="campaign.uid"
              :end="globalStore.dictionaryTimeline[ad.end]"
            />
          </template>
        </ChartTimelineContent>

        <!-- <ChartTimelineContent
            :campaign="campaign"
            :key="campaign.uid"
            v-for="campaign in userDashboardStore.parsedCampaignsList"
          >
            <ChartTimelineItem
              :element="adElement"
              type="ad"
              :is-visible="
                checkIsAdInCollection(campaign.collection, adElement.uid)
              "
              :uid="adElement.uid"
              :color="campaign.color"
              :key="`${adElement.uid}_${Math.random()}`"
              v-for="adElement in campaign.ads"
              :start="globalStore.dictionaryTimeline[adElement.start]"
              :campaing-uid="campaign.uid"
              :end="globalStore.dictionaryTimeline[adElement.end]"
            />
            <ChartTimelineItem
              :element="collection"
              type="collection"
              :color="campaign.color"
              :key="collection.uid"
              v-for="collection in campaign.collection"
              :start="globalStore.dictionaryTimeline[collection.start]"
              :end="globalStore.dictionaryTimeline[collection.end]"
              @collection-selected="(value) => toggleCollection(value)"
            />
          </ChartTimelineContent> -->
      </ChartTimelineWrapper>
      <CollectionBottomBar
        :collection="state.currentCollection"
        @close="toggleCollection()"
      />
    </template>
  </UserDashboardLayout>
</template>
