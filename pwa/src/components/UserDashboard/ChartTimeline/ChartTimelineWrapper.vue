<script setup lang="ts">
import { ref } from "vue";
import { useGlobalStore } from "@/stores/global";
import {
  gapGrid,
  scaleGrid,
  scaleLines,
  heightContent,
  mainWidthGrid,
  scaleFirstGrid,
  scaleLinesGradient,
  changeDynamicalTypeChart
} from "@/helpers/timeline";

type PropsType = {
  hasWeekdays?: boolean;
};

defineProps<PropsType>();

const globalStore = useGlobalStore();
const timelineContent = ref<HTMLElement | null>(null);
const timelineGridInstance = ref<HTMLDivElement | null>(null);

/**
 * Function to handle scale up.
 */
function handleScaleUp() {
  if (globalStore.currentScale >= 200) {
    globalStore.handleChangeScale(200);
    return;
  }

  globalStore.handleChangeScale(globalStore.currentScale + 10);
}

/**
 * Function to handle scale down.
 */
function handleScaleDown() {
  if (globalStore.currentScale <= 60) {
    globalStore.handleChangeScale(60);
    return;
  }

  globalStore.handleChangeScale(globalStore.currentScale - 10);
}

/**
 * Function to check is scroll bar visible.
 * @return {boolean}
 */
function isScrollBarVisible(): boolean {
  return timelineContent.value
    ? timelineContent.value?.scrollWidth > timelineContent.value?.clientWidth
    : true;
}

/**
 * Function to handle change scale.
 * @param {WheelEvent} eventWheel
 */
function handleChangeScale(eventWheel: WheelEvent) {
  if (eventWheel.deltaY > 0) {
    if (isScrollBarVisible()) {
      handleScaleDown();
    }
  } else {
    handleScaleUp();
  }

  changeDynamicalTypeChart();
}
</script>

<template>
  <div
    @wheel.prevent="handleChangeScale"
    class="chart-timeline-wrapper bg-timeline-background grid grid-rows-[1fr] w-fit h-full whitespace-nowrap relative"
    :class="{ 'chart-timeline-wrapper--weekdays': hasWeekdays }"
  >
    <div
      ref="timelineGridInstance"
      class="chart-timeline-wrapper__grid absolute top-4 left-0 z-10"
    >
      <slot />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.chart-timeline-wrapper {
  min-height: v-bind(heightContent);
  width: v-bind(mainWidthGrid);
  grid-template-columns: repeat(auto-fill, v-bind(scaleLines));

  background: repeating-linear-gradient(
    90deg,
    #f9f9fb 0px,
    #f9f9fb v-bind(scaleLines),
    #f4f4f6 v-bind(scaleLines),
    #f4f4f6 v-bind(scaleLinesGradient)
  );

  &--weekdays {
    @apply bg-one;
  }

  &__grid {
    width: v-bind(mainWidthGrid);

    :deep(.chart-timeline-content) {
      display: grid;
      width: v-bind(mainWidthGrid);
      grid-template-columns: v-bind(scaleFirstGrid) repeat(
          auto-fill,
          v-bind(scaleGrid)
        );
      grid-column-gap: v-bind(gapGrid);
      grid-row-gap: 5px;
    }
  }
}
</style>
