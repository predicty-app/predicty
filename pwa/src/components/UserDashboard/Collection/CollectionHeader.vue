<script setup lang="ts">
import { useI18n } from "vue-i18n";
import type { AdsCollection } from "@/stores/userDashboard";
import { ref } from "vue";

const { t } = useI18n();

type PropsType = {
  collection?: AdsCollection;
};

defineProps<PropsType>();

type ProviderType = {
  name: string;
  logoPath: string;
  status: boolean;
  token?: string;
};

const providersImagesPath = "/assets/images/providers/";

const providersList = ref<ProviderType[]>([
  {
    name: "Google Analytics",
    logoPath: "google-analytics-provider",
    status: false
  },
  {
    name: "Google Ads",
    logoPath: "google-ads-provider",
    status: false
  },
  {
    name: "Meta Ads",
    logoPath: "meta-ads-provider",
    status: false
  }
]);
</script>

<template>
  <div class="collection-header flex justify-between">
    <div class="flex items-center">
      <h1 class="font-semibold text-xl">
        {{ collection.name }}
      </h1>
      <span
        class="ml-2 py-0.5 px-1 bg-bottombar-grey rounded text-bottombar-text text-xs"
        >{{ collection.ads.length }}</span
      >
    </div>
    <div class="ml-14 flex items-center">
      <p class="text-bottombar-text text-xs">
        {{ t("components.user-dashboard.bottom-bar.source") }}:
      </p>
      <button
        class="p-2 ml-2 rounded-md border border-bottombar-grey focus:bg-bottombar-hover/50 hover:bg-bottombar-hover/50"
        v-for="provider in providersList"
        :key="provider.name"
        :aria-label="provider.name"
      >
        <img
          :src="`${providersImagesPath}${provider.logoPath}${
            provider.status ? '-active' : ''
          }.png`"
          :class="[
            'w-6',
            {
              'opacity-70': provider.status
            }
          ]"
          :alt="provider.name"
        />
      </button>
    </div>
  </div>
</template>
