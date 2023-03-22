unction to handle scale down.
<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { useGlobalStore } from "@/stores/global";
import {
  scaleGrid,
  scaleFirstGrid,
  gapGrid,
  handleVirtualizationElement
} from "@/helpers/timeline";

const globalStore = useGlobalStore();
const isElementVisible = ref<boolean>(true);
const boundingBoxElement = ref<DOMRect | null>(null);
const barChartPoleContentInstance = ref<HTMLDivElement | null>(null);

onMounted(() => {
  boundingBoxElement.value =
    barChartPoleContentInstance.value.getBoundingClientRect();
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
    v-if="isElementVisible"
    ref="barChartPoleContentInstance"
    class="bar-chart-pole-content w-full"
  >
    <BarChartPoleItem :height="200" />
    <BarChartPoleItem :height="100" />
    <BarChartPoleItem :height="50" />
    <BarChartPoleItem :height="150" />
    <BarChartPoleItem :height="45" />
    <BarChartPoleItem :height="45" />
    <BarChartPoleItem :height="45" />
  </div>
</template>

<style scoped lang="scss">
.bar-chart-pole-content {
  display: grid;
  grid-template-columns: v-bind(scaleFirstGrid) repeat(
      auto-fill,
      v-bind(scaleGrid)
    );
  grid-column-gap: v-bind(gapGrid);
}
</style>
