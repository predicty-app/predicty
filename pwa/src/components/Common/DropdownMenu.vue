<script setup lang="ts">
import { ref } from "vue";

const isMenuVisible = ref<boolean>(false);
</script>

<template>
  <div
    :class="['relative z-20 bg-dropdown-background']"
    tabindex="0"
    @focusout="isMenuVisible = false"
  >
    <div
      :class="[
        'flex items-center gap-x-4 p-[12px] pr-[30px] rounded-t-lg transition-all cursor-pointer',
        {
          'dropdown-shadow': isMenuVisible,
        },
      ]"
      @click="isMenuVisible = !isMenuVisible"
    >
      <slot />
    </div>
    <div
      v-if="isMenuVisible"
      class="absolute top-full text-left w-full shadow-lg z-40 bg-dropdown-background rounded-b-lg animate-fade-in"
    >
      <DividerLine />
      <div class="py-3">
        <slot name="overlayer" />
      </div>
    </div>
    <IconSvg
      name="arrowright"
      :class-name="[
        'fill-select-input-text w-2 h-2 absolute top-0 bottom-0 right-[10px] m-auto transition-all',
        {
          'rotate-90': isMenuVisible,
          'rotate-[-90deg]': !isMenuVisible,
        },
      ]"
    />
  </div>
</template>
