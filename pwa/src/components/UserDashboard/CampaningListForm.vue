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
  const response = await handleGetCampaigns();
  userDashboardStore.setCampaignsList(response);
  nextTick(() => {
    userDashboardStore.handleVirtualizeCampaignsList();
    isSpinnerVisible.value = false;
  });
});

/**
 * Function to calculate active ads.
 * @param {CampaignType} campaign
 * @return {number}
 */
function calculateActiveCurrentAds(campaign: CampaignType): number {
  let count = 0;
  const adsets = campaign.adsets.filter((adset: AdSetsType) => adset.isActive);
  adsets.forEach((adset: AdSetsType) => {
    count += adset.ads.filter((ad: AdsType) => ad.isActive).length;
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
        '--top': `${calculateItemPosition(campaign, 10)}px`
      }"
      :header="campaign.name"
      :color="campaign.color"
      :key="campaign.uid"
      v-for="campaign in userDashboardStore.parsedCampaignsList"
    >
      <div v-if="campaign.isCollection">
        {{
          t(
            "components.user-dashboard.campaning-list-form.active_ad_collection",
            {
              count: campaign.adsets.filter(
                (adset: AdSetsType) => adset.isActive
              ).length
            }
          )
        }}
      </div>
      <div>
        {{
          t("components.user-dashboard.campaning-list-form.active_ad_sets", {
            count: campaign.adsets.filter((adset: AdSetsType) => adset.isActive)
              .length
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
