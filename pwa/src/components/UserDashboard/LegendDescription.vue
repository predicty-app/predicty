<script setup lang="ts">
import { ref } from "vue";

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

const currentTypeChart = ref<string>("weeks");

withDefaults(defineProps<PropsType>(), {
  amountScale: () => ["$ 1,000", "$ 500", "$ 250"],
});
</script>

<template>
  <div
    class="h-full flex flex-col min-h-full items-end pr-7 pt-[15px] pb-[10px] bg-legendDescription-background border-b border-b-legendDescription-border"
  >
    <div class="text-[10px] font-medium text-legendDescription-header pb-5">
      <SelectForm
        class="w-20"
        :options="typeChartOptions"
        v-model="currentTypeChart"
      />
    </div>
    <div class="h-full flex flex-col justify-between">
      <div
        class="text-[10px] font-medium text-legendDescription-scale-text"
        :key="amount"
        v-for="amount in amountScale"
      >
        {{ amount }}
      </div>
    </div>
    <div class="justify-end pt-14">
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
