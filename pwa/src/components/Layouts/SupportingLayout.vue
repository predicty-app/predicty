<script setup lang="ts">
import { useSlots } from "vue";
import { useGlobalStore } from "@/stores/global";

const slots = useSlots();
const globalStore = useGlobalStore();
</script>

<template>
  <SpinnerBar :is-visible="globalStore.isSpinnerVisible" :is-global="true" />
  <div
    class="h-full flex flex-col min-h-full animate-fade-in bg-layout-onboarding-background"
  >
    <HeaderDashboard />
    <div
      class="flex-auto flex-col self-center gap-y-6 h-0 overflow-y-auto flex justify-center max-w-[600px] w-full px-3 pt-[130px]"
    >
      <slot name="content" />
    </div>
    <div
      v-if="slots.footer"
      class="fixed bottom-0 w-full py-4 px-8 flex gap-x-10"
    >
      <slot name="footer" />
    </div>
  </div>
</template>
