<script setup lang="ts">
import { onMounted } from "vue";
import { useGlobalStore } from "@/stores/global";
import {
  scaleLines,
  scaleLinesGradient,
  mainWidthGrid,
} from "@/helpers/timeline";

const globalStore = useGlobalStore();
let firstWeekChart;

onMounted(() => {
  firstWeekChart = globalStore.numberFirstWeek - 1;
});

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
    class="bar-chart-weeks text-center w-full pb-3 pt-6 text-chartBar-text text-xs font-medium"
  >
    <BarChartWeeksItem
      :key="`days_${item}`"
      class="col-start-dynamic col-end-dynamic"
      v-for="(item, index) in globalStore.currentsCountWeeks"
      :style="{ '--start': index + 1, '--end': index + 2 }"
    >
      W{{ calculateWeek() }}
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
