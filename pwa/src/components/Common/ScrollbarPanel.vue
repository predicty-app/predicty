<script setup lang="ts">
import { ref, watch } from "vue";
import { useScroll } from "@vueuse/core";
import type { UseScrollReturn } from "@vueuse/core";
import { vScroll } from "@vueuse/components";

type ScrollType = {
  x: number;
  y: number;
};

type PropsType = {
  scrollX?: number;
  scrollY?: number;
  isHorizontalScrollVisible?: boolean;
  isVerticalScrollVisible?: boolean;
};

const props = withDefaults(defineProps<PropsType>(), {
  isHorizontalScrollVisible: false,
  isVerticalScrollVisible: false,
});

const scrollInstance = ref<HTMLDivElement | null>(null);
const { x, y } = useScroll(scrollInstance);
const isScrollbarsVisible = ref<boolean>(false);

const emit = defineEmits<{
  (e: "onChange", value: ScrollType): void;
}>();

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
  emit("onChange", {
    x: state.x.value,
    y: state.y.value,
  });
}
</script>

<template>
  <div
    data-virtualization="true"
    ref="scrollInstance"
    v-scroll="handleScroll"
    :class="[
      'scroll-bar whitespace-nowrap overflow-hidden',
      {
        'overflow-x-auto overflow-y-auto':
          isScrollbarsVisible &&
          (isHorizontalScrollVisible || isVerticalScrollVisible),
        'overflow-y-scroll': isHorizontalScrollVisible,
        'overflow-x-scroll': isVerticalScrollVisible,
      },
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

  &::-webkit-scrollbar {
    width: 8px;
    height: 8px;
    scrollbar-color: #8a8a8a #edf0f3;
  }

  &::-webkit-scrollbar-track {
    background: #edf0f3;
  }

  &::-webkit-scrollbar-thumb {
    background-color: #8a8a8a;
    border-radius: 10px;
    border: 1px solid #edf0f3;
  }
}
</style>
