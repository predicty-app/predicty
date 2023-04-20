<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useGlobalStore } from "@/stores/global";
import { scaleLines, mainWidthGrid } from "@/helpers/timeline";

const globalStore = useGlobalStore();
const wrapperPoleInstance = ref<HTMLDivElement | null>(null);

onMounted(() => {
  globalStore.wrapperPole = wrapperPoleInstance.value;
});
</script>

<template>
  <div
    ref="wrapperPoleInstance"
    class="bar-chart-pole-wrapper flex items-end relative z-[2]"
  >
    <template
      v-if="Object.keys(globalStore.dictionaryFirstDaysWeek).length > 0"
    >
      <BarChartPoleLines />
      <BarChartPoleContent
        :key="`${Math.random()}_${item}_${index}`"
        class="col-start-dynamic col-end-dynamic"
        v-for="(item, index) in globalStore.currentsCountWeeks"
        :fisrt-day-week="globalStore.dictionaryFirstDaysWeek[item - 1]"
        :style="{ '--start': index + 1, '--end': index + 2 }"
      />
    </template>
  </div>
</template>

<style lang="scss" scoped>
.bar-chart-pole-wrapper {
  display: grid;
  width: v-bind(mainWidthGrid);
  grid-template-columns: repeat(auto-fill, v-bind(scaleLines));
}
</style>
