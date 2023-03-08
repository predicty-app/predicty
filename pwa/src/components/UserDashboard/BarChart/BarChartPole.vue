unction to handle scale down.
<script setup lang="ts">
import { ref } from "vue";

type PropsType = {
  height?: number;
  sales?: string | number;
  investment?: string | number;
  date?: string;
  day?: string;
};

withDefaults(defineProps<PropsType>(), {
  height: 0,
});

const isHoverElement = ref<boolean>(false);
</script>

<template>
  <PopoverPanel :height="height">
    <div
      @mouseenter="isHoverElement = true"
      @mouseleave="isHoverElement = false"
      class="px-[2px]"
    >
      <div
        :class="[
          'w-full h-dynamic hover:shadow-md hover:shadow-charBarPole-hover-shadow transition-colors bg-charBarPole-background-primary hover:bg-charBarPole-hover-background rounded-3xl',
          {
            'bg-gradient-to-b from-charBarPole-background-primary to-charBarPole-background-secondary':
              !isHoverElement,
          },
        ]"
        :style="{ '--height': `${height}px` }"
      ></div>
    </div>
    <template #overlayer>
      <SalesNumber
        sales="$5,345"
        investment="$345"
        date="2023.03.01"
        day="wednesday"
      />
    </template>
  </PopoverPanel>
</template>
