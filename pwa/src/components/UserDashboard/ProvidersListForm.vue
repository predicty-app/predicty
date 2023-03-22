<script setup lang="ts">
import { ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useUserDashboardStore } from "@/stores/userDashboard";

type OptionsType = {
  key: string;
  icon?: string;
  label: string;
};

const pathImages = "/public/assets/images/providers";

const userDashboardStore = useUserDashboardStore();
const modelSelectedProviders = ref<string[]>(
  userDashboardStore.activeProviders
);
const { t } = useI18n();

const providersListOptions: OptionsType[] = [
  {
    key: "google-analytics",
    label: t("components.user-dashboard.providers-list-form.google-analytics"),
    icon: `${pathImages}/google-analytics-provider.png`
  },
  {
    key: "google-ads",
    label: t("components.user-dashboard.providers-list-form.google-ads"),
    icon: `${pathImages}/google-ads-provider.png`
  },
  {
    key: "meta",
    label: t("components.user-dashboard.providers-list-form.meta"),
    icon: `${pathImages}/meta-ads-provider.png`
  }
];

watch(modelSelectedProviders, () => {
  userDashboardStore.handleSetVisibleProviders(modelSelectedProviders.value);
});
</script>

<template>
  <div class="py-6 px-9">
    <MultiSelectForm
      v-model="modelSelectedProviders"
      :options="providersListOptions"
    />
  </div>
</template>
