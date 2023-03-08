<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useGlobalStore } from "@/stores/global";
import { TypeOptionsChart } from "@/stores/userDashboard";
import type { AdsCollection } from "@/stores/userDashboard";
import { useUserDashboardStore } from "@/stores/userDashboard";

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
    color: "#4184FF",
  },
  {
    label: t("views.user-dashboard-view.legend-description.options.investment"),
    color: "#FFAE4F",
  },
];

const chartTypeOptions: TypesOptionsChart[] = [
  {
    key: TypeOptionsChart.WEEKS,
    label: t("views.user-dashboard-view.legend-description.chart-types.weeks"),
  },
  {
    key: TypeOptionsChart.DAYS,
    label: t("views.user-dashboard-view.legend-description.chart-types.days"),
  },
];

const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();

/**
 * Function to check is ad in collection.
 * @param {AdsCollection[]} collections
 * @param {string} adUid
 * @return {boolean}
 */
function checkIsAdInCollection(
  collections: AdsCollection[],
  adUid: string
): boolean {
  return collections.find((collection: AdsCollection) =>
    collection.ads.includes(adUid)
  )
    ? false
    : true;
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
    <template #chart>
      <BarChartWrapper />
    </template>
    <template #ads-campaigns>
      <CampaningListForm />
    </template>
    <template #ads-chart>
      <ChartTimelineWrapper>
        <ChartTimelineContent
          :count-elements="campaign.ads.length + campaign.collection.length"
          :key="campaign.uid"
          v-for="campaign in userDashboardStore.campaigns"
        >
          <ChartTimelineItem
            :is-visible="
              checkIsAdInCollection(campaign.collection, adElement.uid)
            "
            :element="adElement"
            type="ad"
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
          />
        </ChartTimelineContent>
      </ChartTimelineWrapper>
    </template>
  </UserDashboardLayout>
</template>
