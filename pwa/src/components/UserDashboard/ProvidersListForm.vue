<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { ref, watch, onMounted, computed } from "vue";
import { useUserDashboardStore } from "@/stores/userDashboard";
import {
  handleGetProvidersList,
  type ProviderType
} from "@/services/api/providers";

type OptionsType = {
  key: string;
  icon?: string;
  label: string;
};

const pathImages = "/public/assets/images/providers";

const providersList = ref<ProviderType[]>([]);
const userDashboardStore = useUserDashboardStore();
const modelSelectedProviders = ref<string[]>(
  userDashboardStore.activeProviders
);
const { t } = useI18n();
const parseProvidersList = computed<OptionsType[]>(() =>
  providersList.value.map((provider: ProviderType) => ({
    key: provider.id,
    icon: `${pathImages}/${provider.id.toLocaleLowerCase()}.png`,
    label: t(
      `components.user-dashboard.providers-list-form.provider.${provider.id}`
    )
  }))
);

onMounted(async () => {
  providersList.value = await handleGetProvidersList();
});

watch(modelSelectedProviders, () => {
  userDashboardStore.handleSetVisibleProviders(modelSelectedProviders.value);
});
</script>

<template>
  <div class="py-6 px-9">
    <MultiSelectForm
      v-model="modelSelectedProviders"
      :options="parseProvidersList"
    />
  </div>
</template>
