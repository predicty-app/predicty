<script setup lang="ts">
import type { AdSetsType } from "@/stores/userDashboard";

type PropsType = {
  collection?: AdSetsType;
};

defineProps<PropsType>();
</script>

<template>
  <div class="flex items-center py-[18px] px-5 gap-x-3">
    <h1 class="font-semibold text-xl">
      {{ collection.name }}
    </h1>
    <span
      class="ml-2 py-0.5 px-1 bg-bottombar-grey rounded text-bottombar-text text-xs"
    >
      {{ collection.ads.length }}
    </span>
  </div>
</template>
