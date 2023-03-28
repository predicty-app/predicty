<script setup lang="ts">
import { ref } from "vue";
import { useGlobalStore } from "@/stores/global";
import {
  gapGrid,
  scaleGrid,
  scaleLines,
  mainWidthGrid,
  scaleFirstGrid,
  scaleLinesGradient,
  heightCollectionContent,
  changeDynamicalTypeChart
} from "@/helpers/timeline";

const globalStore = useGlobalStore();
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
 * Function to handle change scale.
 * @param {WheelEvent} eventWheel
 */
function handleChangeScale(eventWheel: WheelEvent) {
  if (!globalStore.isZoomActive) {
    return;
  }

  if (eventWheel.deltaY > 0) {
    handleScaleDown();
  } else {
    handleScaleUp();
  }

  changeDynamicalTypeChart();
}

/**
 * Set active zoom state.
 */
function handleActiveZoomState(e: KeyboardEvent) {
  if (e.key !== "Shift") {
    return;
  }
  if (!globalStore.isZoomActive) {
    globalStore.isZoomActive = true;
  }
}

/**
 * Set deactive zoom state.
 */
function handleDeActiveZoomState(e: KeyboardEvent) {
  if (e.key !== "Shift") {
    return;
  }
  if (globalStore.isZoomActive) {
    globalStore.isZoomActive = false;
  }
}
</script>

<template>
  <div
    @wheel="handleChangeScale"
    @keydown="handleActiveZoomState"
    @keyup="handleDeActiveZoomState"
    tabindex="0"
    class="collection-timeline outline-none bg-timeline-background grid grid-rows-[1fr] w-fit h-full whitespace-nowrap relative"
  >
    <div
      ref="timelineGridInstance"
      class="collection-timeline__grid absolute top-4 left-0 z-10 animate-fade-in"
    >
      <slot />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.collection-timeline {
  width: v-bind(mainWidthGrid);
  min-height: v-bind(heightCollectionContent);
  grid-template-columns: repeat(auto-fill, v-bind(scaleLines));

  background: repeating-linear-gradient(
    90deg,
    #f9f9fb 0px,
    #f9f9fb v-bind(scaleLines),
    #f4f4f6 v-bind(scaleLines),
    #f4f4f6 v-bind(scaleLinesGradient)
  );

  @apply bg-one;

  &__grid {
    display: grid;
    width: v-bind(mainWidthGrid);
    grid-template-columns: v-bind(scaleFirstGrid) repeat(
        auto-fill,
        v-bind(scaleGrid)
      );
    grid-column-gap: v-bind(gapGrid);
    grid-row-gap: 16px;
  }
}
</style>
