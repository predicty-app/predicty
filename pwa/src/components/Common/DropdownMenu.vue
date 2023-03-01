<script setup lang="ts">
import { ref } from "vue";

const isMenuVisible = ref<boolean>(false);
</script>

<template>
  <div :class="['relative z-20 bg-dropdown-background']" tabindex="0">
    <div
      :class="[
        'flex items-center gap-x-4 p-[12px] rounded-t-lg transition-all cursor-pointer',
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
      class="absolute top-full text-left w-full shadow-lg z-20 bg-dropdown-background rounded-b-lg animate-fade-in"
    >
      <DividerLine />
      <div class="py-3">
        <slot name="overlayer" />
      </div>
    </div>
  </div>
</template>
