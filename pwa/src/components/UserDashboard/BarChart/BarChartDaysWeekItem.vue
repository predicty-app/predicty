<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { ref, onMounted, watch } from "vue";
import { useGlobalStore } from "@/stores/global";
import {
  scaleGrid,
  scaleFirstGrid,
  gapGrid,
  handleVirtualizationElement,
} from "@/helpers/timeline";

const globalStore = useGlobalStore();
const isElementVisible = ref<boolean>(true);
const boundingBoxElement = ref<DOMRect | null>(null);
const chartDaysWeekItemInstance = ref<HTMLDivElement | null>(null);

onMounted(() => {
  boundingBoxElement.value =
    chartDaysWeekItemInstance.value.getBoundingClientRect();
  isElementVisible.value = handleVirtualizationElement(
    boundingBoxElement.value
  );
});

const weeksDaysDictionary: string[] = [
  "mon",
  "tue",
  "wed",
  "thu",
  "fri",
  "sat",
  "sun",
];

const { t } = useI18n();

watch(
  () => [
    globalStore.scrollParams,
    globalStore.scrollTimeline,
    globalStore.currentScale,
  ],
  () =>
    (isElementVisible.value = handleVirtualizationElement(
      boundingBoxElement.value
    ))
);
</script>

<template>
  <div
    class="bar-chart-days-week-item animate-fade-in"
    ref="chartDaysWeekItemInstance"
    v-if="isElementVisible"
  >
    <div
      :class="[
        'rotate-[-90deg] text-[8px] font-medium flex justify-center',
        {
          ' text-chartBar-weeks-text': day !== 'sun',
          ' text-chartBar-weeks-sunday': day === 'sun',
        },
      ]"
      :key="`${Math.random()}_${day}`"
      v-for="day in weeksDaysDictionary"
    >
      {{ t(`components.user-dashboard.bar-chart.weeks-days.${day}`) }}
    </div>
  </div>
</template>

<style scoped lang="scss">
.bar-chart-days-week-item {
  display: grid;
  grid-template-columns: v-bind(scaleFirstGrid) repeat(
      auto-fill,
      v-bind(scaleGrid)
    );
  grid-column-gap: v-bind(gapGrid);
}
</style>
