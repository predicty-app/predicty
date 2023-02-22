<script setup lang="ts">
import { useSlots } from 'vue';

const slots = useSlots()
</script>

<template>
  <div class="h-full flex flex-col min-h-full animate-fade-in bg-layout-onboarding-background">
    <div v-if="slots.header" class="mt-12 flex justify-center">
      <slot name="header" />
    </div>
    <div class="flex-auto h-0 overflow-y-auto flex justify-center items-center">
      <slot name="content" />
    </div>
    <div v-if="slots.footer" class="w-full  bg-text-white py-4 px-8 border-t border-t-layout-onboarding-border">
      <slot name="footer" />
    </div>
  </div>
</template>