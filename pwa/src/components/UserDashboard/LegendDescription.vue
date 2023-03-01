<script setup lang="ts">
type options = {
  label: string;
  color: string;
};

type PropsType = {
  header?: string;
  options?: options[];
};

defineProps<PropsType>();
</script>

<template>
  <div
    class="h-full flex flex-col min-h-full items-end pr-7 py-[26px] bg-legendDescription-background"
  >
    <div
      v-if="header"
      class="text-[10px] font-medium text-legendDescription-header"
    >
      {{ header }}
    </div>
    <div class="h-full flex flex-col"></div>
    <div class="justify-end">
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
