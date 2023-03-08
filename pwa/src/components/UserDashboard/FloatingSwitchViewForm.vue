<script setup lang="ts">
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import { useUserDashboardStore, OptionsName } from "@/stores/userDashboard";
import type { CampaignType } from "@/stores/userDashboard";

type OptionsType = {
  key: string;
  label: string;
};

const { t } = useI18n();
const userDashboardStore = useUserDashboardStore();
const optionsButtons = computed<OptionsType[]>(() => {
  const options: OptionsType[] = [
    {
      key: OptionsName.CREATE_NEW_COLLECTION,
      label: t(
        "components.user-dashboard.floating-switch-view-form.create-new-collection"
      ),
    },
    {
      key: OptionsName.HIDE_ELEMENT,
      label: t(
        "components.user-dashboard.floating-switch-view-form.hide-element"
      ),
    },
  ];

  const campaign = userDashboardStore.campaigns.find(
    (campaing: CampaignType) =>
      campaing.uid === userDashboardStore.selectedAdsList.campaignUid
  );

  if (campaign.collection.length > 0) {
    options.push({
      key: OptionsName.ADD_TO_COLLECTION,
      label: t(
        "components.user-dashboard.floating-switch-view-form.add-to-collection"
      ),
    });
  }

  return options;
});

/**
 * Function to start action.
 * @param {OptionsName} optionName
 */
function handleFiredAction(actionName: OptionsName) {
  console.log(actionName);
  //selectedAds
}
</script>

<template>
  <FloatingPanel
    class="absolute bottom-3 right-3 m-auto animate-fade-in z-20"
    :selected-elements="userDashboardStore.selectedAdsList.ads.length"
    :options="optionsButtons"
    @on-click="handleFiredAction"
  />
</template>
