<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { onMounted, nextTick } from "vue";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { handleGetCampaigns } from "@/services/api/userDashboard";
import type { CampaignType, AdsType } from "@/stores/userDashboard";
import {
  heightContent,
  calculateItemHeight,
  calculateItemPosition
} from "@/helpers/timeline";

const { t } = useI18n();
const userDashboardStore = useUserDashboardStore();

onMounted(async () => {
  const response = await handleGetCampaigns();
  userDashboardStore.setCampaignsList(response);
  nextTick(() => {
    userDashboardStore.handleVirtualizeCampaignsList();
  });
});

/**
 * Function to calculate active ads.
 * @param {CampaignType} campaign
 * @return {number}
 */
function calculateActiveCurrentAds(campaign: CampaignType): number {
  const currentTimestamp = Math.floor(Date.now() / 1000);
  let count = 0;

  campaign.ads.forEach((ad: AdsType) => {
    if (
      Date.parse(ad.start) / 1000 < currentTimestamp &&
      Date.parse(ad.end) / 1000 > currentTimestamp
    ) {
      count++;
    }
  });

  return count;
}
</script>

<template>
  <div class="px-9 h-dynamic relative" :style="{ '--height': heightContent }">
    <CampaningListItem
      class="campaign-list-item h-dynamic absolute animate-fade-in"
      :style="{
        '--height': `${calculateItemHeight(campaign)}px`,
        '--top': `${calculateItemPosition(campaign, 10)}px`
      }"
      :header="campaign.name"
      :color="campaign.color"
      :key="campaign.uid"
      v-for="campaign in userDashboardStore.parsedCampaignsList"
    >
      <div>
        {{
          t(
            "components.user-dashboard.campaning-list-form.active_ad_collection",
            {
              count: campaign.collection.length
            }
          )
        }}
      </div>
      <div>
        {{
          t("components.user-dashboard.campaning-list-form.active_ad_sets", {
            count: 0
          })
        }}
      </div>
      <div>
        {{
          t("components.user-dashboard.campaning-list-form.active_ads", {
            count: calculateActiveCurrentAds(campaign)
          })
        }}
      </div>
    </CampaningListItem>
  </div>
</template>

<style lang="scss">
.campaign-list-item {
  top: var(--top);
}
</style>
