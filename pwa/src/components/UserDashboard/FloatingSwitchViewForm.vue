<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";
import type { CampaignType, AdSetsType } from "@/stores/userDashboard";
import { useUserDashboardStore, OptionsName } from "@/stores/userDashboard";
import {
  handleCreateCollection,
  handleAssignAdToCollection
} from "@/services/api/userDashboard";

type OptionsType = {
  key: string;
  label: string;
};

const { t } = useI18n();
const campaignModelValue = ref<string>("");
const userDashboardStore = useUserDashboardStore();
const optionsCollectionList = computed<OptionsType[]>(() => {
  return userDashboardStore.campaigns[0].adsets.map((adsets: AdSetsType) => ({
    key: adsets.uid,
    label: adsets.name
  }));
});

const optionsButtons = computed<OptionsType[]>(() => {
  const options: OptionsType[] = [
    {
      key: OptionsName.CREATE_NEW_COLLECTION,
      label: t(
        "components.user-dashboard.floating-switch-view-form.create-new-collection"
      )
    },
    {
      key: OptionsName.HIDE_ELEMENT,
      label: t(
        "components.user-dashboard.floating-switch-view-form.hide-element"
      )
    }
  ];

  if (userDashboardStore.campaigns[0].adsets.length > 0) {
    options.push({
      key: OptionsName.ADD_TO_COLLECTION,
      label: t(
        "components.user-dashboard.floating-switch-view-form.add-to-collection"
      )
    });
  }

  return options;
});

/**
 * Function to start action.
 * @param {OptionsName} optionName
 */
function handleFiredAction(actionName: OptionsName) {
  switch (actionName) {
    case "create_new_collection":
      {
        handleCreateCollection({
          campaignUid: userDashboardStore.selectedAdsList.campaignUid,
          ads: userDashboardStore.selectedAdsList.ads
        });

        userDashboardStore.selectedAdsList.ads = [];
        userDashboardStore.selectedAdsList.campaignUid = null;
      }
      break;
    case "add_to_collection":
      {
        handleAssignAdToCollection({
          campaignUid: userDashboardStore.selectedAdsList.campaignUid,
          collectionUid: campaignModelValue.value,
          ads: userDashboardStore.selectedAdsList.ads
        });
      }
      break;
    case "hide_element":
      {
        userDashboardStore.toogleVisibilityAds(
          userDashboardStore.selectedAdsList.ads
        );
      }
      break;
  }

  userDashboardStore.selectedAdsList.ads = [];
  userDashboardStore.selectedAdsList.campaignUid = null;
}
</script>

<template>
  <FloatingPanel
    class="absolute bottom-3 right-3 m-auto animate-fade-in z-20"
    :selected-elements="userDashboardStore.selectedAdsList.ads.length"
    :options="optionsButtons"
    @on-click="handleFiredAction"
  >
    <template #additional>
      <SelectForm
        class="w-44 animate-fade-in"
        v-model="campaignModelValue"
        position="top"
        :options="optionsCollectionList"
        :placeholder="
          t(
            'components.user-dashboard.floating-switch-view-form.select-placeholder'
          )
        "
      />
    </template>
  </FloatingPanel>
</template>
