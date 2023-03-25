<script setup lang="ts">
import { ref, onMounted } from "vue";
import {
  useUserDashboardStore,
  TypeOptionsChart
} from "@/stores/userDashboard";

type PropsType = {
  day?: string;
  date?: string;
  height?: number;
  type?: TypeOptionsChart;
  sales?: string | number;
  result?: number;
  investment?: string | number;
};

const props = withDefaults(defineProps<PropsType>(), {
  height: 0,
  result: 0,
  type: TypeOptionsChart.DAYS
});

const isHoverElement = ref<boolean>(false);
const userDashboardStore = useUserDashboardStore();
const currentHeightActive = ref<number>(0);

onMounted(() => {
  setTimeout(() => {
    currentHeightActive.value = props.result;
  }, 100);
});
</script>

<template>
  <PopoverPanel
    :height="height"
    @mouseenter="isHoverElement = true"
    @mouseleave="isHoverElement = false"
  >
    <div class="px-[2px]">
      <div
        v-if="
          userDashboardStore.selectedAdsList.ads.length === 0 &&
          !userDashboardStore.selectedCollection
        "
        :class="[
          'h-dynamic absolute animate-fade-in hover:shadow-md hover:shadow-charBarPole-hover-shadow transition-colors bg-charBarPole-background-primary hover:bg-charBarPole-hover-background',
          {
            'bg-gradient-to-b from-charBarPole-background-primary to-charBarPole-background-secondary':
              !isHoverElement,
            'w-[80%] rounded-3xl': type === TypeOptionsChart.DAYS,
            'w-[95%] left-1 rounded-xl': type === TypeOptionsChart.WEEKS
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
          'h-dynamic overflow-hidden absolute hover:shadow-md hover:bg-charBarPole-hover-disabled bg-charBarPole-background-disabled',
          {
            'bg-charBarPole-hover-disabled': isHoverElement,
            'w-[80%] rounded-3xl': type === TypeOptionsChart.DAYS,
            'w-[95%] left-1 rounded-xl': type === TypeOptionsChart.WEEKS
          }
        ]"
        :style="{ '--height': `${height}px` }"
      >
        <div
          v-if="
            userDashboardStore.selectedAdsList.ads.length > 0 ||
            userDashboardStore.selectedCollection
          "
          :class="[
            'bottom-0 h-dynamic absolute transition-all bg-charBarPole-background-active w-[100%] rounded-b',
            {
              ' drop-shadow-lg shadow-charBarPole-hover-active bg-charBarPole-hover-active':
                isHoverElement
            }
          ]"
          :style="{ '--height': `${currentHeightActive}px` }"
        ></div>
      </div>
    </div>
    <!-- <template #overlayer>
      <SalesNumber :sales="`$${height.toFixed(2)}`" :investment="`$${Number(investment).toFixed(2)}`" date="2023.03.01" day="wednesday" />
    </template> -->
  </PopoverPanel>
</template>
