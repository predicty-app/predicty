<script setup lang="ts">
import { onMounted } from "vue";
import { useI18n } from "vue-i18n";
import type { CampaignType, AdsType } from "@/stores/userDashboard";
import { handleGetCampaigns } from "@/services/api/userDashboard";
import { useUserDashboardStore } from "@/stores/userDashboard";

const { t } = useI18n();
const userDashboardStore = useUserDashboardStore();

onMounted(async () => {
  const response = await handleGetCampaigns();
  userDashboardStore.setCampaignsList(response);
});

/**
 * Function to calculate height of box item campain.
 * @param {CampaignType} campaign
 * @return {number}
 */
function calculateItemHeight(campaign: CampaignType): number {
  return (
    (campaign.ads.length + campaign.collection.length) * 36 +
    (campaign.ads.length + campaign.collection.length) * 5
  );
}

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
  <div class="px-9 pb-8">
    <CampaningListItem
      class="h-dynamic mb-[7px] my-[7px]"
      :style="{ '--height': `${calculateItemHeight(campaign)}px` }"
      :header="campaign.name"
      :color="campaign.color"
      :key="campaign.uid"
      v-for="campaign in userDashboardStore.campaigns"
    >
      <div>
        {{
          t(
            "components.user-dashboard.campaning-list-form.active_ad_collection",
            {
              count: campaign.collection.length,
            }
          )
        }}
      </div>
      <div>
        {{
          t("components.user-dashboard.campaning-list-form.active_ad_sets", {
            count: 0,
          })
        }}
      </div>
      <div>
        {{
          t("components.user-dashboard.campaning-list-form.active_ads", {
            count: calculateActiveCurrentAds(campaign),
          })
        }}
      </div>
    </CampaningListItem>
  </div>
</template>
