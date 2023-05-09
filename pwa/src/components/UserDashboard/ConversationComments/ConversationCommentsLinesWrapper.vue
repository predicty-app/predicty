<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { scaleLines, mainWidthGrid } from "@/helpers/timeline";

const globalStore = useGlobalStore();
</script>
<template>
  <div class="absolute w-full h-[99%] left-0 top-0">
    <ScrollbarPanel class="h-[99%]" :scroll-x="globalStore.scrollParams.x">
      <div
        v-if="Object.keys(globalStore.dictionaryFirstDaysWeek).length > 0"
        class="conversation-comments-lines-wrapper h-[100%]"
      >
        <ConversationCommentsLines
          :key="`lines_${item}`"
          class="col-start-dynamic col-end-dynamic"
          v-for="(item, index) in globalStore.currentsCountWeeks"
          :style="{ '--start': index + 1, '--end': index + 2 }"
          :conversation-date="globalStore.dictionaryFirstDaysWeek[item - 1]"
        />
      </div>
    </ScrollbarPanel>
  </div>
</template>
<style scoped lang="scss">
.conversation-comments-lines-wrapper {
  display: grid;
  width: v-bind(mainWidthGrid);
  grid-template-columns: repeat(auto-fill, v-bind(scaleLines));
}
</style>
