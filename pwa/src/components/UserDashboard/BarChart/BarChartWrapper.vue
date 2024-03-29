<script setup lang="ts">
import { ref } from "vue";
import { useGlobalStore } from "@/stores/global";
import {
  scaleLines,
  mainWidthGrid,
  scaleLinesGradient,
  changeDynamicalTypeChart
} from "@/helpers/timeline";

const globalStore = useGlobalStore();
const timelineContent = ref<HTMLElement | null>(null);

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
    class="bar-chart-wrapper grid grid-rows-[1fr] w-fit h-full whitespace-nowrap relative"
  >
    <div
      class="w-max absolute top-0 left-0 z-[1] pt-0 pb-0 justify-between h-full flex flex-col min-h-full"
    >
      <div
        class="h-[1px] w-max border-t border-t-gray-300 absolute top-[0px]"
      ></div>
      <div
        class="h-[1px] w-max border-t border-t-gray-300 absolute bottom-0 top-0 m-auto"
      ></div>
      <div
        class="h-[1px] w-max border-t border-t-gray-300 pb-[0px] absolute bottom-[70px]"
      ></div>
    </div>
    <BarChartPoleWrapper />
  </div>
</template>

<style lang="scss">
.bar-chart-wrapper {
  width: v-bind(mainWidthGrid);
  grid-template-columns: repeat(auto-fill, v-bind(scaleLines));

  background: repeating-linear-gradient(
    90deg,
    #f9f9fb 0px,
    #f9f9fb v-bind(scaleLines),
    #f4f4f6 v-bind(scaleLines),
    #f4f4f6 v-bind(scaleLinesGradient)
  );

  &__grid {
    width: v-bind(mainWidthGrid);
  }
}
</style>
