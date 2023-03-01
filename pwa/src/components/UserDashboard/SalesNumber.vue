<script setup lang="ts">
import { useI18n } from "vue-i18n";

type PropsType = {
  date?: string;
  sales?: string | number;
  investment?: string | number;
};

defineProps<PropsType>();

const { t } = useI18n();
</script>

<template>
  <div class="flex flex-col gap-y-[6px] items-center">
    <span v-if="date" class="text-[10px] text-salesNumber-date text-center">{{
      date
    }}</span>
    <div v-if="sales" class="flex flex-col text-center">
      <div
        class="text-[10px] font-medium text-salesNumber-sale-header uppercase font-ibm-mono mb-[-4px]"
      >
        {{ t("components.user-dashboard.sales-number.sales") }}
      </div>
      <div class="text-base font-bold text-salesNumber-sale-text font-ibm-sans">
        {{ sales }}
      </div>
    </div>
    <div v-if="investment" class="flex flex-col text-center">
      <div
        class="text-[10px] font-medium text-salesNumber-investment-header uppercase font-ibm-mono mb-[-4px]"
      >
        {{ t("components.user-dashboard.sales-number.investment") }}
      </div>
      <div
        class="text-base font-bold text-salesNumber-investment-text font-ibm-sans"
      >
        {{ investment }}
      </div>
    </div>
  </div>
</template>
