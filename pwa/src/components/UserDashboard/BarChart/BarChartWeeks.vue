<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import {
  scaleLines,
  mainWidthGrid,
  scaleLinesGradient
} from "@/helpers/timeline";

const globalStore = useGlobalStore();
</script>

<template>
  <div
    class="bar-chart-weeks text-center w-full pb-8 pt-6 text-gray-700 text-xs font-medium"
  >
    <BarChartWeeksItem
      :key="`days_${item}`"
      class="col-start-dynamic col-end-dynamic"
      v-for="(item, index) in globalStore.currentsCountWeeks"
      :style="{ '--start': index + 1, '--end': index + 2 }"
    >
      W1
    </BarChartWeeksItem>
  </div>
</template>

<style lang="scss" scoped>
.bar-chart-weeks {
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
