<script setup lang="ts">
import { useScroll } from "@vueuse/core";
import { ref, watch, onMounted } from "vue";
import { vScroll } from "@vueuse/components";
import type { UseScrollReturn } from "@vueuse/core";

type ScrollType = {
  x: number;
  y: number;
  direction?: "horizontal" | "vertical" | null | string;
};

type PropsType = {
  scrollX?: number;
  scrollY?: number;
  staticScrollX?: number;
  isHorizontalScrollVisible?: boolean;
  isVerticalScrollVisible?: boolean;
  isOverflowHidden?: boolean;
};

const props = withDefaults(defineProps<PropsType>(), {
  isHorizontalScrollVisible: false,
  isVerticalScrollVisible: false,
  isOverflowHidden: true
});

const scrollInstance = ref<HTMLDivElement | null>(null);
const { x, y } = useScroll(scrollInstance);
const isScrollbarsVisible = ref<boolean>(false);

const emit = defineEmits<{
  (e: "onChange", value: ScrollType): void;
  (e: "onMounted", value: HTMLDivElement): void;
}>();

onMounted(() => {
  emit("onMounted", scrollInstance.value);
  x.value = props.staticScrollX;
});

watch(
  () => props.scrollX,
  () => {
    x.value = props.scrollX;
  }
);

watch(
  () => props.scrollY,
  () => {
    y.value = props.scrollY;
  }
);

/**
 * Function handle scroll element.
 * @param {UseScrollReturn} state
 */
function handleScroll(state: UseScrollReturn) {
  if (props.scrollX || props.scrollY) {
    return;
  }

  let direction =
    state.directions.left || state.directions.right ? "horizontal" : null;
  direction =
    state.directions.top || state.directions.left ? "vertical" : direction;

  emit("onChange", {
    x: state.x.value,
    y: state.y.value,
    direction
  });
}
</script>

<template>
  <div
    data-virtualization="true"
    ref="scrollInstance"
    v-scroll="handleScroll"
    :class="[
      'scroll-bar whitespace-nowrap',
      {
        'overflow-x-auto overflow-y-auto':
          isScrollbarsVisible &&
          (isHorizontalScrollVisible || isVerticalScrollVisible),
        'overflow-y-scroll': isHorizontalScrollVisible,
        'overflow-x-scroll': isVerticalScrollVisible,
        'overflow-hidden': isOverflowHidden,
      }
    ]"
    @mouseenter="isScrollbarsVisible = true"
    @mouseleave="isScrollbarsVisible = false"
  >
    <slot />
  </div>
</template>

<style lang="scss">
.scroll-bar {
  scrollbar-color: #8a8a8a #edf0f3;
  scrollbar-width: thin;
  border-radius: 10px;

  &::-webkit-scrollbar {
    width: 8px;
    height: 8px;
    scrollbar-color: #8a8a8a #edf0f3;
    border-radius: 10px;
  }

  &::-webkit-scrollbar-track {
    background: #edf0f3;
    border-radius: 10px;
  }

  &::-webkit-scrollbar-thumb {
    background-color: #8a8a8a;
    border-radius: 10px;
    border: 1px solid #edf0f3;
  }
}
</style>
