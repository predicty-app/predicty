<script setup lang="ts">
import { useSlots } from "vue";
import { useGlobalStore } from "@/stores/global";

const slots = useSlots();
const globalStore = useGlobalStore();
</script>

<template>
  <SpinnerBar :is-visible="globalStore.isSpinnerVisible" :is-global="true" />
  <div
    class="h-full flex flex-col items-center min-h-full animate-fade-in bg-layout-onboarding-background"
  >
    <div v-if="slots.header" class="pt-12 flex justify-center pb-6">
      <slot name="header" />
    </div>
    <div
      class="flex-auto justify-center flex-col gap-y-6 h-0 overflow-y-auto flex max-w-[660px] w-full px-3"
    >
      <slot name="content" />
    </div>
    <div v-if="slots.footer" class="w-full py-4 px-8 flex gap-x-10">
      <slot name="footer" />
    </div>
    <div v-if="slots.progress" class="w-full">
      <slot name="progress" />
    </div>
  </div>
</template>
