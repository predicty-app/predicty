<script setup lang="ts">
import { ref } from "vue";
import { useUserDashboardStore } from "@/stores/userDashboard";

type PropsType = {
  day?: string;
  date?: string;
  height?: number;
  sales?: string | number;
  result?: number;
  investment?: string | number;
};

withDefaults(defineProps<PropsType>(), {
  height: 0
});

const isHoverElement = ref<boolean>(false);
const userDashboardStore = useUserDashboardStore();
</script>

<template>
  <PopoverPanel :height="height">
    <div
      @mouseenter="isHoverElement = true"
      @mouseleave="isHoverElement = false"
      class="px-[2px]"
    >
      <div
        v-if="
          userDashboardStore.selectedAdsList.ads.length === 0 &&
          !userDashboardStore.selectedCollection
        "
        :class="[
          'w-[80%] h-dynamic absolute animate-fade-in hover:shadow-md hover:shadow-charBarPole-hover-shadow transition-colors bg-charBarPole-background-primary hover:bg-charBarPole-hover-background rounded-3xl',
          {
            'bg-gradient-to-b from-charBarPole-background-primary to-charBarPole-background-secondary':
              !isHoverElement
          }
        ]"
        :style="{ '--height': `${height}px` }"
      ></div>

      <div
        v-if="
          userDashboardStore.selectedAdsList.ads.length > 0 ||
          userDashboardStore.selectedCollection
        "
        :class="[
          'w-[80%] h-dynamic absolute hover:shadow-md hover:bg-charBarPole-hover-disabled bg-charBarPole-background-disabled rounded-3xl'
        ]"
        :style="{ '--height': `${height}px` }"
      >
        <div
          class="w-full bottom-0 h-dynamic absolute transition-all bg-charBarPole-background-active rounded-b-3xl"
          :style="{ '--height': `${result}px` }"
        ></div>
      </div>
    </div>
    <template #overlayer>
      <SalesNumber
        sales="$5,345"
        :investment="result"
        date="2023.03.01"
        day="wednesday"
      />
    </template>
  </PopoverPanel>
</template>
