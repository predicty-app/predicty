<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import { useGlobalStore } from "@/stores/global";

type PropsType = {
  heightInner: string;
};

type ScrollType = {
  x: number;
  y: number;
  direction: "horizontal" | "vertical" | null | string;
};

const scrollY = ref<number>(0);
const props = defineProps<PropsType>();
const globalStore = useGlobalStore();
const currentHeightTimeline = computed<string>(
  () => `${Number(props.heightInner.slice(0, -2)) - 90}px`
);
const currentHeightAdsList = computed<string>(
  () => `${Number(props.heightInner.slice(0, -2)) - 90}px`
);

onMounted(() => {
  handleChangeScrollParams(globalStore.scrollParams as ScrollType);
});

/**
 * Function to hanlde change scroll params.
 * @param {ScrollType} params
 */
function handleChangeScrollParams(params: ScrollType) {
  scrollY.value = params.y;
  globalStore.handleChangeScrollParams(params);
}
</script>

<template>
  <div class="select-none grid grid-cols-[340px_auto]">
    <div
      class="h-calc select-none grid grid-cols-[auto] grid-rows-[60px_30px_auto]"
    >
      <slot name="collection-settings"></slot>
      <div>&nbsp;</div>
      <ScrollbarPanel
        :scroll-y="scrollY"
        class="collection-timeline-ads-content"
      >
        <slot name="collection-ads-list" />
      </ScrollbarPanel>
    </div>
    <div
      :class="`h-calc select-none grid grid-cols-[auto] grid-rows-[60px_30px_auto]`"
    >
      <slot name="collection-providers-list"></slot>
      <ScrollbarPanel :scroll-x="globalStore.scrollParams.x">
        <slot name="collection-ads-weeks" />
      </ScrollbarPanel>
      <ScrollbarPanel
        class="collection-timeline-content"
        @onChange="handleChangeScrollParams"
        :is-vertical-scroll-visible="true"
      >
        <slot name="collection-ads-timeline" />
      </ScrollbarPanel>
    </div>
  </div>
</template>

<style scoped lang="scss">
.collection-timeline-content {
  height: v-bind(currentHeightTimeline);
}

.collection-timeline-ads-content {
  height: v-bind(currentHeightAdsList);
}
</style>
