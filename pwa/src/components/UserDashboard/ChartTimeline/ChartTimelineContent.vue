<script setup lang="ts">
import { computed } from "vue";

type PropsType = {
  countElements?: number;
};

const props = withDefaults(defineProps<PropsType>(), {
  countElements: 0,
});

const calculatedHeightElement = computed<number>(() => {
  return props.countElements * 36 + props.countElements * 5;
});
</script>

<template>
  <div
    class="chart-timeline-content h-dynamic mb-2"
    :style="{ '--height': `${calculatedHeightElement}px` }"
  >
    <slot />
  </div>
</template>
