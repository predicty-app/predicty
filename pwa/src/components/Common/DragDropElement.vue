<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { ref, nextTick } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { AdSetsType, AdsType } from "@/stores/userDashboard";
import {
  handleGetCampaigns,
  handleAssignAdToCollection
} from "@/services/api/userDashboard";

type PropsType = {
  isActiveDrag: boolean;
  isActiveDrop: boolean;
  element: AdSetsType | AdsType;
};

type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const { t } = useI18n();
const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();
const props = withDefaults(defineProps<PropsType>(), {
  isActiveDrag: false,
  isActiveDrop: false
});
const isSpinnerVisible = ref<boolean>(false);
const notificationMessageModel = ref<NotificationMessageType>({
  visible: false,
  type: "success",
  message: ""
});

/**
 * Function to set response after one of actions - assign or create collection.
 * @param {'create-collection' | 'assign-ads-to-collection'} type
 * @param {any} response
 */
async function setResponseFiredAction(
  type: "assign-ads-to-collection",
  response: any
) {
  notificationMessageModel.value.visible = true;
  notificationMessageModel.value.type = response ? "success" : "error";
  notificationMessageModel.value.message = t(
    `components.user-dashboard.floating-switch-view-form.notifications.${type}.${response ? "success" : "error"
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
 * Function to handle active action.
 * @param {'drag' | 'drop'} action
 */
async function toggleActiveAction(action: "drag" | "drop") {
  switch (action) {
    case "drag":
      {
        handleActionForDrag();
      }
      break;
    case "drop":
      {
        handleActionForDrop();
      }
      break;
  }
}

/**
 * Function to handle action for drag element.
 */
async function handleActionForDrag() {
  if (
    !userDashboardStore.activeProviders.includes(
      props.element.dataProvider[0]
    ) ||
    !props.isActiveDrag
  ) {
    return;
  }

  globalStore.isActiveActionDrag = true;
  userDashboardStore.toogleAssignAdsAction(null, props.element.uid);
}

/**
 * Function to handle action for drop element.
 */
async function handleActionForDrop() {
  if (
    !props.isActiveDrop ||
    !globalStore.isActiveActionDrag ||
    userDashboardStore.selectedAdsList.ads.length === 0
  ) {
    return;
  }
  globalStore.isActiveActionDrag = false;
  isSpinnerVisible.value = true;
  const response = await handleAssignAdToCollection({
    campaignUid: userDashboardStore.selectedAdsList.campaignUid,
    collectionUid: props.element.uid,
    ads: userDashboardStore.selectedAdsList.ads
  });

  await setResponseFiredAction("assign-ads-to-collection", response);
}
</script>

<template>
  <SpinnerBar :is-visible="isSpinnerVisible" :is-global="true" />
  <NotificationMessage v-model="notificationMessageModel.visible" :message="notificationMessageModel.message"
    :type="notificationMessageModel.type" />
  <div @mousedown="toggleActiveAction('drag')" @mouseup="toggleActiveAction('drop')">
    <slot />
  </div>
</template>
