<script setup lang="ts">
import { ref, computed } from "vue";
import { useElementSize } from "@vueuse/core";
import { useGlobalStore } from "@/stores/global";

type PropsType = {
  hasWeekdays?: boolean;
};

defineProps<PropsType>();

const globalStore = useGlobalStore();

const timelineContent = ref<HTMLElement | null>(null);
const gapGrid = computed<string>(
  () => `${5 * (globalStore.currentScale * 0.01)}px`
);
const scaleLines = computed<string>(
  () => `${150 * (globalStore.currentScale * 0.01)}px`
);
const scaleGrid = computed<string>(
  () => `${16.4 * (globalStore.currentScale * 0.01)}px`
);
const scaleFirstGrid = computed<string>(
  () => `${16 * (globalStore.currentScale * 0.01)}px`
);
const mainWidthGrid = computed<string>(
  () =>
    `${
      globalStore.currentsCountWeeks * (150 * (globalStore.currentScale * 0.01))
    }px`
);
const timelineGridInstance = ref<HTMLDivElement | null>(null);
const { height } = useElementSize(timelineGridInstance);

const heightContent = computed<string>(() => `${height.value + 68}px`);

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
}
</script>

<template>
  <div
    @wheel.prevent="handleChangeScale"
    class="chart-timeline-wrapper bg-timeline-background grid grid-rows-[1fr] w-fit h-full whitespace-nowrap relative"
  >
    <div
      :class="[
        'border-r border-timeline-lines-border',
        {
          'bg-light': item % 2 !== 0 && hasWeekdays,
          'bg-dark': item % 2 === 0 && hasWeekdays,
          'bg-timeline-lines-background_primary':
            item % 2 !== 0 && !hasWeekdays,
          'bg-timeline-lines-background_secondary':
            item % 2 === 0 && !hasWeekdays,
        },
      ]"
      :key="`line_${item}`"
      v-for="item in globalStore.currentsCountWeeks"
    >
      <div class="pb-5 pt-1 px-2 text-sm font-bold text-timeline-lines-text">
        {{ globalStore.dictionaryFirstDaysWeek[item] }}
      </div>
    </div>
    <div
      ref="timelineGridInstance"
      class="chart-timeline-wrapper__grid absolute top-[50px] left-0 z-10"
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

  &__grid {
    width: v-bind(mainWidthGrid);

    :deep(.chart-timeline-content) {
      display: grid;
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
