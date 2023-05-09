<script setup lang="ts">
import {
  scaleGrid,
  scaleFirstGrid,
  gapGrid,
  handleVirtualizationElement
} from "@/helpers/timeline";
import { ref, onMounted, watch } from "vue";
import { useGlobalStore } from "@/stores/global";

type PropsType = {
  conversationDate: string;
};

const globalStore = useGlobalStore();
const props = defineProps<PropsType>();
const isElementVisible = ref<boolean>(true);
const boundingBoxElement = ref<DOMRect | null>(null);
const conversationCommentLine = ref<HTMLDivElement | null>(null);

onMounted(() => {
  boundingBoxElement.value =
    conversationCommentLine.value.getBoundingClientRect();
  isElementVisible.value = handleVirtualizationElement(
    boundingBoxElement.value
  );
});

watch(
  () => [
    globalStore.scrollParams,
    globalStore.scrollTimeline,
    globalStore.currentScale
  ],
  () =>
    (isElementVisible.value = handleVirtualizationElement(
      boundingBoxElement.value
    ))
);

function parseDateByDay(day: number): string {
  const dateToParse = `${props.conversationDate.slice(
    6,
    10
  )}-${props.conversationDate.slice(3, 5)}-${props.conversationDate.slice(
    0,
    2
  )}`;
  const date = new Date(dateToParse);
  date.setDate(date.getDate() + day);

  return `${date.getFullYear()}-${
    date.getMonth() + 1 < 10 ? `0${date.getMonth() + 1}` : date.getMonth() + 1
  }-${date.getDate() < 10 ? `0${date.getDate()}` : date.getDate()}`;
}
</script>
<template>
  <div
    ref="conversationCommentLine"
    class="conversation-comments-lines"
    v-if="isElementVisible"
  >
    <ConversationCommentsLine
      :key="parseDateByDay(day - 1)"
      :conversation-date="parseDateByDay(day - 1)"
      v-for="day in 7"
    />
  </div>
</template>

<style scoped lang="scss">
.conversation-comments-lines {
  display: grid;
  grid-template-columns: v-bind(scaleFirstGrid) repeat(
      auto-fill,
      v-bind(scaleGrid)
    );
  grid-column-gap: v-bind(gapGrid);
}
</style>
