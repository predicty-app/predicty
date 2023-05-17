<script setup lang="ts">
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import type { AdSetsType } from "@/stores/userDashboard";

type PropsType = {
  collection?: AdSetsType;
};

const props = defineProps<PropsType>();

type OptionsType = {
  key: string;
  icon?: string;
};

const { t } = useI18n();
const pathImages = "/public/assets/images/providers";

const providersList = computed<OptionsType[]>(() =>
  (props.collection.dataProvider as string[]).map((provider: string) => ({
    key: provider,
    icon: `${pathImages}/${provider.toLocaleLowerCase()}.png`
  }))
);
</script>

<template>
  <div class="py-[18px] flex items-center gap-x-[10px]">
    <span class="text-xs font-medium text-gray-900">{{
      t("components.user-dashboard.collection-providers-list.source")
    }}</span>
    <div
      :key="`collection-provider-${provider.key}`"
      class="w-9 h-9 cursor-pointer hover:border-green-400 transition border-gray-300 border rounded bg-basic-white flex items-center justify-center"
      v-for="provider in providersList"
    >
      <img class="w-5 h-5" :src="provider.icon" />
    </div>
  </div>
</template>
