<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useUserDashboardStore, ViewNames } from "@/stores/userDashboard";

type OptionsType = {
  key: string;
  label: string;
};

const { t } = useI18n();
const userDashboardStore = useUserDashboardStore();

const optionsButtons: OptionsType[] = [
  {
    key: ViewNames.AD,
    label: t("components.user-dashboard.floating-switch-view-form.option-ads"),
  },
  {
    key: ViewNames.AD_COLLECTION,
    label: t(
      "components.user-dashboard.floating-switch-view-form.option-ad-collection"
    ),
  },
];

/**
 * Function to change view.
 * @param {ViewNames} viewName
 */
function handleChangeView(viewName: ViewNames) {
  userDashboardStore.currentView = viewName;
}
</script>

<template>
  <FloatingPanel
    class="absolute bottom-5 left-0 right-0 m-auto"
    :options="optionsButtons"
    @on-click="handleChangeView"
    :active-key="userDashboardStore.currentView"
  />
</template>
