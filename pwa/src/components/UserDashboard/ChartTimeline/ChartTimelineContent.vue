<script setup lang="ts">
import type { CampaignType } from "@/stores/userDashboard";
import { calculateItemPosition, calculateItemHeight } from "@/helpers/timeline";

type PropsType = {
  fixedHeight?: boolean;
  campaign?: CampaignType;
};

defineProps<PropsType>();
</script>

<template>
  <div
    :class="[
      'chart-timeline-content-main',
      {
        'h-dynamic absolute': !fixedHeight,
        'h-[52px]': fixedHeight
      }
    ]"
    :style="[
      !fixedHeight
        ? {
            '--height': `${calculateItemHeight(campaign)}px`,
            '--top': `${calculateItemPosition(campaign, 12)}px`
          }
        : ''
    ]"
  >
    <div class="chart-timeline-content animate-fade-in h-full">
      <slot />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.chart-timeline-content-main {
  top: var(--top);
}
</style>
