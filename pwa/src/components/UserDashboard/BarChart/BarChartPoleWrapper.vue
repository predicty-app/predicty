<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { scaleLines, mainWidthGrid } from "@/helpers/timeline";

const globalStore = useGlobalStore();
</script>

<template>
  <div class="bar-chart-pole-wrapper flex items-end relative z-[2]">
    <BarChartPoleContent
      :key="`${Math.random()}_${item}_${index}`"
      class="col-start-dynamic col-end-dynamic"
      v-for="(item, index) in globalStore.currentsCountWeeks"
      :fisrt-day-week="globalStore.dictionaryFirstDaysWeek[item - 1]"
      :style="{ '--start': index + 1, '--end': index + 2 }"
    />
  </div>
</template>

<style lang="scss" scoped>
.bar-chart-pole-wrapper {
  display: grid;
  width: v-bind(mainWidthGrid);
  grid-template-columns: repeat(auto-fill, v-bind(scaleLines));
}
</style>
