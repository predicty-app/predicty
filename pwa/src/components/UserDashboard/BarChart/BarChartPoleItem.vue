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

const tooltipPositionX = ref<string>("0px");
const tooltipPositionY = ref<string>("0px");
const isHoverElement = ref<boolean>(false);
const userDashboardStore = useUserDashboardStore();
const currentHeightActive = ref<number>(0);
const isTooltipVisible = ref<boolean>(false);
const tooltTipReference = ref<HTMLDivElement | null>(null);

onMounted(() => {
  setTimeout(() => {
    currentHeightActive.value = props.result;
  }, 100);
});

function handleShowTooltipPosition(event: MouseEvent) {
  tooltipPositionX.value = `${
    event.clientX - tooltTipReference.value.getBoundingClientRect().width / 2
  }px`;
  tooltipPositionY.value = `${
    event.clientY - tooltTipReference.value.getBoundingClientRect().height - 20
  }px`;
}
</script>

<template>
  <div class="relative flex items-end">
    <div class="h-dynamic w-full" :style="{ '--height': `${height}px` }">
      <div class="px-[2px]">
        <div
          ref="tooltTipReference"
          v-if="isTooltipVisible"
          class="bg-popover-background drop-shadow-md rounded-xl z-[9999] fixed animate-fade-in text-center py-[10px] px-3 top-dynamic"
          :style="{ top: tooltipPositionY, left: tooltipPositionX }"
        >
          <IconSvg
            name="triangle"
            class-name="absolute w-3 h-3 bottom-[-9px] m-auto left-0 right-0"
          />
          <SalesNumber
            :sales="sales"
            :investment="investment"
            :day="day"
            :date="date"
            currency="$"
          />
        </div>
        <div
          v-if="
            userDashboardStore.selectedAdsList.ads.length === 0 &&
            !userDashboardStore.selectedCollection
          "
          @mouseenter="isTooltipVisible = true"
          @mouseleave="isTooltipVisible = false"
          @mousemove="handleShowTooltipPosition"
          :class="[
            'h-dynamic z-[1000] absolute animate-fade-in hover:shadow-md hover:shadow-charBarPole-hover-shadow transition-colors hover:bg-charBarPole-hover-background',
            {
              'w-[80%] rounded-3xl': type === TypeOptionsChart.DAYS,
              'w-[95%] left-1 rounded-xl': type === TypeOptionsChart.WEEKS
            }
          ]"
          :style="{ '--height': `${height}px` }"
        ></div>
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
          @mouseenter="isTooltipVisible = true"
          @mouseleave="isTooltipVisible = false"
          @mousemove="handleShowTooltipPosition"
          :class="[
            'h-dynamic z-[1000] overflow-hidden absolute hover:shadow-md hover:bg-charBarPole-hover-disabled ',
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
    </div>
  </div>
</template>
