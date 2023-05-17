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
          class="bg-basic-white drop-shadow-md rounded-xl z-[9999] fixed animate-fade-in text-center py-[10px] px-3 top-dynamic"
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
            'h-dynamic z-[1000] absolute animate-fade-in transition-colors hover:bg-blue-300 hover:drop-shadow-[0_0px_3px_rgba(65,132,255,1)]',
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
            'h-dynamic absolute animate-fade-in transition-colors from-blue-200 to-blue-100 bg-gradient-linear',
            {
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
            'h-dynamic z-[1000] overflow-hidden bg-gray-400/50 absolute hover:bg-gray-400 group',
            {
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
              'bottom-0 h-dynamic absolute transition-all bg-green-400 w-[100%] rounded-b group-hover:bg-green-200 group-hover:drop-shadow-[0_0px_3px_rgba(65,203,89,1)]'
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
            'h-dynamic overflow-hidden absolute',
            {
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
              'bottom-0 h-dynamic absolute transition-all w-[100%] rounded-b',
              {
                ' drop-shadow-lg': isHoverElement
              }
            ]"
            :style="{ '--height': `${currentHeightActive}px` }"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>
