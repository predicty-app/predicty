<script setup lang="ts">
import {
  gapGrid,
  scaleGrid,
  scaleLines,
  heightContent,
  mainWidthGrid,
  scaleFirstGrid,
  scaleLinesGradient
} from "@/helpers/timeline";

type PropsType = {
  hasWeekdays?: boolean;
};

defineProps<PropsType>();
</script>

<template>
  <div
    tabindex="0"
    ref="timelineContent"
    class="chart-timeline-wrapper outline-none bg-timeline-background grid grid-rows-[1fr] w-fit h-full whitespace-nowrap relative"
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
