<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import {
  scaleLines,
  scaleLinesGradient,
  mainWidthGrid
} from "@/helpers/timeline";

type PropsType = {
  hasWeekdays?: boolean;
};

defineProps<PropsType>();

const globalStore = useGlobalStore();
</script>

<template>
  <div
    v-if="Object.keys(globalStore.dictionaryFirstDaysWeek).length > 0"
    class="collection-timeline-weeks pb-5 pt-1 px-2 text-sm font-bold text-timeline-lines-text"
  >
    <div
      :key="`week_${item}`"
      class="col-start-dynamic col-end-dynamic"
      v-for="(item, index) in globalStore.currentsCountWeeks"
      :style="{ '--start': index + 1, '--end': index + 2 }"
    >
      {{ globalStore.dictionaryFirstDaysWeek[item - 1].slice(0, -5) }}
    </div>
  </div>
</template>

<style lang="scss" scoped>
.collection-timeline-weeks {
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

  &--weekdays {
    @apply bg-one;
  }
}
</style>
