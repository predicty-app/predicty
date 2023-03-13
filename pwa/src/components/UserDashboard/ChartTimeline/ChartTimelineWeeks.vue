<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import {
  scaleLines,
  scaleLinesGradient,
  mainWidthGrid,
} from "@/helpers/timeline";

const globalStore = useGlobalStore();
</script>

<template>
  <div
    class="chart-timeline-weeks pb-5 pt-1 px-2 text-sm font-bold text-timeline-lines-text"
  >
    <ChartTimelineWeeksItem
      :key="`week_${item}`"
      class="col-start-dynamic col-end-dynamic"
      v-for="(item, index) in globalStore.currentsCountWeeks"
      :style="{ '--start': index + 1, '--end': index + 2 }"
    >
      {{ globalStore.dictionaryFirstDaysWeek[item] }}
    </ChartTimelineWeeksItem>
  </div>
</template>

<style lang="scss" scoped>
.chart-timeline-weeks {
  display: grid;
  width: v-bind(mainWidthGrid);
  grid-template-columns: repeat(auto-fill, v-bind(scaleLines));

  background: repeating-linear-gradient(
    90deg,
    #f9f9fb 0px,
    #f9f9fb v-bind(scaleLines),
    #f4f4f6 v-bind(scaleLines),
    #f4f4f6 v-bind(scaleLinesGradient)
  );
}
</style>
