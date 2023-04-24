<script setup lang="ts">
import { useI18n } from "vue-i18n";

type PropsType = {
  date?: string;
  day?: string;
  sales?: string | number;
  currency?: string | number;
  investment?: string | number;
};

defineProps<PropsType>();

const { t } = useI18n();
</script>

<template>
  <div class="flex flex-col gap-y-[5px] items-center">
    <div
      v-if="date"
      class="text-[8px] text-salesNumber-date text-center leading-3"
    >
      <div>{{ date }}</div>
      <div>{{ day }}</div>
    </div>
    <div class="flex flex-col text-center">
      <div
        class="text-[10px] font-medium text-salesNumber-sale-header uppercase font-ibm-mono mb-[-4px]"
      >
        {{ t("components.user-dashboard.sales-number.sales") }}
      </div>
      <div class="text-base font-bold text-salesNumber-sale-text font-ibm-sans">
        {{ currency }}{{ sales }}
      </div>
    </div>
    <div class="flex flex-col text-center">
      <div
        class="text-[10px] font-medium text-salesNumber-investment-header uppercase font-ibm-mono mb-[-4px]"
      >
        {{ t("components.user-dashboard.sales-number.investment") }}
      </div>
      <div
        class="text-base font-bold text-salesNumber-investment-text font-ibm-sans"
      >
        {{ currency }}{{ investment }}
      </div>
    </div>
  </div>
</template>
