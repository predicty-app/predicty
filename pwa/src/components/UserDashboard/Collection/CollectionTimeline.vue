<script setup lang="ts">
import {
  gapGrid,
  scaleGrid,
  scaleLines,
  mainWidthGrid,
  scaleFirstGrid,
  scaleLinesGradient,
  heightCollectionContent
} from "@/helpers/timeline";
</script>

<template>
  <div
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
