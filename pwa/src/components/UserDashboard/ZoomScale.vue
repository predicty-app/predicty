<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";

const { t } = useI18n();
const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();

</script>

<template>
  <div :class="['fixed transition-all animate-fade-in-up bottom-3 right-0 z-50 text-sm font-bold bg-zoomScale-background text-zoomScale-text rounded-lg px-3 py-[15px] drop-shadow-sm', {
    'right-[450px]': userDashboardStore.selectedAdsList.ads.length > 0,
    'right-2': userDashboardStore.selectedAdsList.ads.length === 0
  }]">
    {{ t('components.user-dashboard.zoom-scale.title') }}
    {{ globalStore.currentScale }}% 
  </div>
</template>