<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { onMounted, nextTick, ref } from "vue";
import { handleGetCampaigns } from "@/services/api/userDashboard";
import type { CampaignType, AdsType } from "@/stores/userDashboard";
import { useUserDashboardStore, type AdSetsType } from "@/stores/userDashboard";
import {
  heightContent,
  calculateItemHeight,
  calculateItemPosition
} from "@/helpers/timeline";

const { t } = useI18n();
const isSpinnerVisible = ref<boolean>(false);
const userDashboardStore = useUserDashboardStore();

onMounted(async () => {
  isSpinnerVisible.value = true;
  const { campaigns, dailyRevenue, conversations } = await handleGetCampaigns();
  userDashboardStore.setCampaignsList(campaigns);
  userDashboardStore.setDailyReveneu(dailyRevenue);
  userDashboardStore.setConversationsList(conversations);
  nextTick(() => {
    userDashboardStore.handleVirtualizeCampaignsList();
    isSpinnerVisible.value = false;
  });
});

/**
 * Function to calculate active adsets.
 * @param {CampaignType} campaign
 * @return {number}
 */
function calculateActiveCurrentAdSets(campaign: CampaignType): number {
  let count = 0;
  campaign.adSets.forEach((adSets: AdSetsType) => {
    const currentDate = Date.now();
    const toDate = Date.parse(adSets.startedAt);
    const fromDate = Date.parse(adSets.endedAt);

    if (currentDate >= fromDate && currentDate <= toDate) {
      count++;
    }
  });

  return count;
}

/**
 * Function to calculate active ads.
 * @param {CampaignType} campaign
 * @return {number}
 */
function calculateActiveCurrentAds(campaign: CampaignType): number {
  let count = 0;
  campaign.adSets.forEach((adSets: AdSetsType) => {
    adSets.ads.forEach((ad: AdsType) => {
      const currentDate = Date.now();
      const toDate = Date.parse(ad.adStats.at(-1).date);
      const fromDate = Date.parse(ad.adStats.at(0).date);

      if (currentDate >= fromDate && currentDate <= toDate) {
        count++;
      }
    });
  });

  return count;
}
</script>

<template>
  <SpinnerBar :is-visible="isSpinnerVisible" :is-global="true" />
  <div class="px-9 h-dynamic relative" :style="{ '--height': heightContent }">
    <CampaningListItem
      class="campaign-list-item h-dynamic absolute animate-fade-in"
      :style="{
        '--height': `${calculateItemHeight(campaign)}px`,
        '--top': `${calculateItemPosition(campaign, 12)}px`
      }"
      :header="campaign.name"
      :color="campaign.color"
      :key="campaign.id"
      v-for="campaign in userDashboardStore.parsedCampaignsList"
    >
      <div v-if="campaign.isCollection">
        {{
          t(
            "components.user-dashboard.campaning-list-form.active_ad_collection",
            {
              count: 0
            }
          )
        }}
      </div>
      <div>
        {{
          t("components.user-dashboard.campaning-list-form.active_ad_sets", {
            count: calculateActiveCurrentAdSets(campaign)
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
