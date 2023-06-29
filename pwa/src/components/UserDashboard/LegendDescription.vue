<script setup lang="ts">
import { ref, watch } from "vue";
import {
  TypeOptionsChart,
  VisualTypeOptionsChart,
  useUserDashboardStore
} from "@/stores/userDashboard";
import { useI18n } from "vue-i18n";

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

const { t } = useI18n();
const userDashboardStore = useUserDashboardStore();
const currentTypeChart = ref<TypeOptionsChart>(userDashboardStore.typeChart);
const currentVisualTypeChart = ref<VisualTypeOptionsChart>(userDashboardStore.visualTypeChart);

withDefaults(defineProps<PropsType>(), {
  amountScale: () => ["$ 1,000", "$ 500", "$ 250"]
});

const chartVisualTypeOptions: TypesOptionsChart[] = [
  {
    key: VisualTypeOptionsChart.BAR,
    label: t("views.user-dashboard-view.legend-description.visual-chart-types.bar")
  },
  {
    key: VisualTypeOptionsChart.LINE,
    label: t("views.user-dashboard-view.legend-description.visual-chart-types.line")
  }
];

watch(currentTypeChart, () => {
  userDashboardStore.typeChart = currentTypeChart.value;
  userDashboardStore.visualTypeChart = currentVisualTypeChart.value;
});

watch(currentVisualTypeChart, () => {
  userDashboardStore.visualTypeChart = currentVisualTypeChart.value;
});

watch(
  () => userDashboardStore.typeChart,
  () => {
    currentTypeChart.value = userDashboardStore.typeChart;
  }
);

watch(
  () => userDashboardStore.visualTypeChart,
  () => {
    currentVisualTypeChart.value = userDashboardStore.visualTypeChart;
  }
);
</script>

<template>
  <div
    class="h-full relative flex flex-col min-h-full items-end pr-7 pt-[15px] pb-[10px] bg-basic-white border-b border-gray-100"
  >
    <div class="text-[10px] font-medium text-gray-700 pb-[10px] inline-flex">
      <SelectForm
        class="w-20"
        :options="chartVisualTypeOptions"
        v-model="currentVisualTypeChart"
      />
      <SelectForm
        class="w-20"
        :options="typeChartOptions"
        v-model="currentTypeChart"
      />
    </div>
    <div class="h-full flex flex-col justify-between relative w-full">
      <div
        :class="[
          'text-[10px] font-medium text-gray-700 absolute right-0 w-[100px] text-right',
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
        class="uppercase text-[10px] font-medium text-dynamic text-right"
        :style="{ '--color': option.color }"
      >
        {{ option.label }}
      </div>
    </div>
  </div>
</template>
