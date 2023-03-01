<script setup lang="ts">
import { ref } from "vue";

const isVisibleOverlayer = ref<boolean>(false);
</script>

<template>
  <div class="relative">
    <div
      @mouseover="isVisibleOverlayer = true"
      @mouseleave="isVisibleOverlayer = false"
    >
      <slot />
    </div>
    <div
      v-if="isVisibleOverlayer"
      class="w-max bg-popover-background rounded-xl absolute bottom-[150%] animate-fade-in text-center py-[10px] px-3 translate-x-[-50%] left-[50%]"
    >
      <IconSvg
        name="triangle"
        class-name="absolute w-3 h-3 bottom-[-10px] m-auto left-0 right-0"
      />
      <slot name="overlayer" />
    </div>
  </div>
</template>
