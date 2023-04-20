<script setup lang="ts">
import { ref, watch } from "vue";
import {
  TypeOptionsChart,
  useUserDashboardStore
} from "@/stores/userDashboard";

type OptionsType = {
  label: string;
  color: string;
};

type ChartOptionsType = {
  key: string;
  label: string;
};

type PropsType = {
  amountScale?: string[];
  options?: OptionsType[];
  typeChartOptions: ChartOptionsType[];
};

const userDashboardStore = useUserDashboardStore();
const currentTypeChart = ref<TypeOptionsChart>(userDashboardStore.typeChart);

withDefaults(defineProps<PropsType>(), {
  amountScale: () => ["$ 1,000", "$ 500", "$ 250"]
});

watch(currentTypeChart, () => {
  userDashboardStore.typeChart = currentTypeChart.value;
});

watch(
  () => userDashboardStore.typeChart,
  () => {
    currentTypeChart.value = userDashboardStore.typeChart;
  }
);
</script>

<template>
  <div
    class="h-full relative flex flex-col min-h-full items-end pr-7 pt-[15px] pb-[10px] bg-legendDescription-background border-b border-b-legendDescription-border"
  >
    <div
      class="text-[10px] font-medium text-legendDescription-header pb-[10px]"
    >
      <SelectForm
        class="w-20"
        :options="typeChartOptions"
        v-model="currentTypeChart"
      />
    </div>
    <div class="h-full flex flex-col justify-between relative w-full">
      <div
        :class="[
          'text-[10px] font-medium text-legendDescription-scale-text absolute right-0 w-[100px] text-right',
          {
            'top-0': index === 0,
            'top-0 bottom-0 m-auto h-[1px]': index === 1,
            'bottom-[75px] h-[1px]': index === 2
          }
        ]"
        :key="amount"
        v-for="(amount, index) in amountScale"
      >
        {{ amount }}
      </div>
    </div>
    <div class="absolute bottom-0">
      <div
        :key="option.label"
        v-for="option in options"
        class="uppercase text-[10px] font-medium text-legendDescription-option text-right"
        :style="{ '--color': option.color }"
      >
        {{ option.label }}
      </div>
    </div>
  </div>
</template>
