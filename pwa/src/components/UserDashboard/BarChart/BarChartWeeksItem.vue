<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { useGlobalStore } from "@/stores/global";
import { handleVirtualizationElement } from "@/helpers/timeline";

const globalStore = useGlobalStore();
const isElementVisible = ref<boolean>(true);
const boundingBoxElement = ref<DOMRect | null>(null);
const chartWeekskItemInstance = ref<HTMLDivElement | null>(null);

onMounted(() => {
  boundingBoxElement.value =
    chartWeekskItemInstance.value.getBoundingClientRect();
  isElementVisible.value = handleVirtualizationElement(
    boundingBoxElement.value
  );
});

watch(
  () => [
    globalStore.scrollParams,
    globalStore.scrollTimeline,
    globalStore.currentScale
  ],
  () =>
    (isElementVisible.value = handleVirtualizationElement(
      boundingBoxElement.value
    ))
);
</script>

<template>
  <div
    ref="chartWeekskItemInstance"
    class="animate-fade-in"
    v-if="isElementVisible"
  >
    <slot />
  </div>
</template>
