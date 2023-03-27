<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { computed, ref, nextTick } from "vue";
import type { AdSetsType } from "@/stores/userDashboard";
import { handleGetCampaigns } from "@/services/api/userDashboard";
import { useUserDashboardStore, OptionsName } from "@/stores/userDashboard";
import {
  handleCreateCollection,
  handleAssignAdToCollection,
  handleUnAssignAdFromCollection
} from "@/services/api/userDashboard";

type OptionsType = {
  key: string;
  label: string;
};

type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const { t } = useI18n();
const campaignModelValue = ref<string>("");
const isSpinnerVisible = ref<boolean>(false);
const notificationMessageModel = ref<NotificationMessageType>({
  visible: false,
  type: "success",
  message: ""
});
const userDashboardStore = useUserDashboardStore();
const optionsCollectionList = computed<OptionsType[]>(() => {
  return userDashboardStore.campaigns[0].adsets.map((adsets: AdSetsType) => ({
    key: adsets.uid,
    label: adsets.name
  }));
});

const optionsButtons = computed<OptionsType[]>(() => {
  const options: OptionsType[] = [
    !userDashboardStore.selectedCollection && {
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
  ].filter(Boolean);

  if (
    userDashboardStore.campaigns[0].isCollection &&
    userDashboardStore.campaigns[0].adsets.length > 0 &&
    !userDashboardStore.selectedCollection
  ) {
    options.push({
      key: OptionsName.ADD_TO_COLLECTION,
      label: t(
        "components.user-dashboard.floating-switch-view-form.add-to-collection"
      )
    });
  }

  if (userDashboardStore.selectedCollection) {
    options.push({
      key: OptionsName.REMOVE_FROM_COLLECTION,
      label: t(
        "components.user-dashboard.floating-switch-view-form.remove-from-collection"
      )
    });
  }

  return options;
});

/**
 * Function to set response after one of actions - assign or create collection.
 * @param {'create-collection' | 'assign-ads-to-collection'} type
 * @param {any} response
 */
async function setResponseFiredAction(
  type:
    | "create-collection"
    | "assign-ads-to-collection"
    | "unassign-ads-from-collection",
  response: any
) {
  notificationMessageModel.value.visible = true;
  notificationMessageModel.value.type = response ? "success" : "error";
  notificationMessageModel.value.message = t(
    `components.user-dashboard.floating-switch-view-form.notifications.${type}.${
      response ? "success" : "error"
    }`
  );

  if (response) {
    const { campaigns, dailyRevenue } = await handleGetCampaigns();
    userDashboardStore.setCampaignsList(campaigns);
    userDashboardStore.setDailyReveneu(dailyRevenue);
    nextTick(() => {
      userDashboardStore.handleVirtualizeCampaignsList();
      isSpinnerVisible.value = false;
    });
  }

  isSpinnerVisible.value = false;

  userDashboardStore.selectedAdsList.ads = [];
  userDashboardStore.selectedCollectionAdsList.ads = [];
  userDashboardStore.selectedAdsList.campaignUid = null;
}

/**
 * Function to handle selected remove ads.
 */
function handleRemoveSelectedAds() {
  userDashboardStore.selectedAdsList.ads = [];
  userDashboardStore.selectedCollectionAdsList.ads = [];
  userDashboardStore.selectedAdsList.campaignUid = null;
}

/**
 * Function to start action.
 * @param {OptionsName} optionName
 */
async function handleFiredAction(actionName: OptionsName) {
  switch (actionName) {
    case OptionsName.CREATE_NEW_COLLECTION:
      {
        isSpinnerVisible.value = true;
        const response = await handleCreateCollection({
          campaignUid: userDashboardStore.selectedAdsList.campaignUid,
          ads: userDashboardStore.selectedAdsList.ads
        });

        await setResponseFiredAction("create-collection", response);
      }
      break;
    case OptionsName.ADD_TO_COLLECTION:
      {
        isSpinnerVisible.value = true;
        const response = await handleAssignAdToCollection({
          campaignUid: userDashboardStore.selectedAdsList.campaignUid,
          collectionUid: campaignModelValue.value,
          ads: userDashboardStore.selectedAdsList.ads
        });

        await setResponseFiredAction("assign-ads-to-collection", response);
      }
      break;
    case OptionsName.REMOVE_FROM_COLLECTION:
      {
        isSpinnerVisible.value = true;
        const response = await handleUnAssignAdFromCollection({
          campaignUid: null,
          collectionUid: userDashboardStore.selectedCollection.uid,
          ads: userDashboardStore.selectedCollectionAdsList.ads
        });

        await setResponseFiredAction("unassign-ads-from-collection", response);
        const collection = userDashboardStore.campaigns[0].adsets.find(
          (adsets: AdSetsType) =>
            adsets.uid === userDashboardStore.selectedCollection.uid
        );
        if (collection) {
          userDashboardStore.selectedCollection = JSON.parse(
            JSON.stringify(collection)
          );
        } else {
          userDashboardStore.selectedCollection = null;
        }
      }
      break;
    case OptionsName.HIDE_ELEMENT:
      {
        userDashboardStore.toogleVisibilityAds(
          userDashboardStore.selectedAdsList.ads.length > 0
            ? userDashboardStore.selectedAdsList.ads
            : userDashboardStore.selectedCollectionAdsList.ads
        );
        userDashboardStore.selectedAdsList.ads = [];
        userDashboardStore.selectedCollectionAdsList.ads = [];
      }
      break;
  }
}
</script>

<template>
  <SpinnerBar :is-visible="isSpinnerVisible" :is-global="true" />
  <NotificationMessage
    v-model="notificationMessageModel.visible"
    :message="notificationMessageModel.message"
    :type="notificationMessageModel.type"
  />
  <FloatingPanel
    class="absolute bottom-3 right-3 m-auto animate-fade-in-up z-20"
    :selected-elements="userDashboardStore.selectedCollection ? userDashboardStore.selectedCollectionAdsList.ads.length : userDashboardStore.selectedAdsList.ads.length"
    :options="optionsButtons"
    @on-remove="handleRemoveSelectedAds"
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
