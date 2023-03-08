<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { useGlobalStore } from "@/stores/global";

const globalStore = useGlobalStore();

const { t } = useI18n();

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

let firstWeekChart = 0;

const weeksDaysDictionary: string[] = [
  "mon",
  "tue",
  "wed",
  "thu",
  "fri",
  "sat",
  "sun",
];

onMounted(() => {
  firstWeekChart = globalStore.numberFirstWeek - 1;
});

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

/**
 * Function calculate week.
 * @return {number}
 */
function calculateWeek(): number {
  firstWeekChart = firstWeekChart >= 52 ? 1 : (firstWeekChart += 1);
  return firstWeekChart;
}
</script>

<template>
  <div
    @wheel.prevent="handleChangeScale"
    class="bar-chart-wrapper bg-timeline-background grid grid-rows-[1fr] w-fit h-full whitespace-nowrap relative"
  >
    <div
      class="w-calc absolute top-0 left-0 z-[1] pt-[80px] pb-[155px] justify-between h-full flex flex-col min-h-full"
    >
      <div class="h-[1px] w-calc border-t border-t-chartBar-lines"></div>
      <div class="h-[1px] w-calc border-t border-t-chartBar-lines"></div>
      <div class="h-[1px] w-calc border-t border-t-chartBar-lines"></div>
    </div>
    <div
      :class="[
        'relative border-r border-timeline-lines-border',
        {
          'bg-timeline-lines-background_primary': item % 2 !== 0,
          'bg-timeline-lines-background_secondary': item % 2 === 0,
        },
      ]"
      :key="`line_${item}`"
      v-for="item in globalStore.currentsCountWeeks"
    >
      <div
        class="absolute text-center w-full pt-6 text-chartBar-text text-xs font-medium"
      >
        W{{ calculateWeek() }}
      </div>
      <div class="bar-chart-wrapper__content z-20 absolute w-full bottom-3">
        <div
          :class="[
            'rotate-[-90deg] text-[8px] font-medium',
            {
              ' text-chartBar-weeks-text': day !== 'sun',
              ' text-chartBar-weeks-sunday': day === 'sun',
            },
          ]"
          :key="`${item}_${day}`"
          v-for="day in weeksDaysDictionary"
        >
          {{ t(`components.user-dashboard.bar-chart.weeks-days.${day}`) }}
        </div>
      </div>
      <div
        class="bar-chart-wrapper__content z-40 absolute w-full bottom-[45px]"
      >
        <BarChartPole :height="150" />
        <BarChartPole :height="50" />
        <BarChartPole :height="45" />
        <BarChartPole :height="120" />
        <BarChartPole :height="90" />
        <BarChartPole :height="70" />
        <BarChartPole :height="20" />
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.bar-chart-wrapper {
  width: v-bind(mainWidthGrid);
  grid-template-columns: repeat(auto-fill, v-bind(scaleLines));

  &__grid {
    width: v-bind(mainWidthGrid);
  }

  &__content {
    display: grid;
    grid-template-columns: v-bind(scaleFirstGrid) repeat(
        auto-fill,
        v-bind(scaleGrid)
      );
    grid-column-gap: v-bind(gapGrid);
  }
}
</style>
