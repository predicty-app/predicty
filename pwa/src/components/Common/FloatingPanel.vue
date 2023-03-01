<script setup lang="ts">
type OptionsType = {
  key: string;
  label: string;
};

type PropsType = {
  options: OptionsType[];
  activeKey: string;
};

defineProps<PropsType>();

defineEmits<{
  (e: "onClick", value: string): void;
}>();
</script>

<template>
  <div
    class="bg-floatingPanel-background rounded-full px-[5px] py-1 shadow-lg flex max-w-max gap-x-1"
  >
    <div
      @click="
        activeKey !== option.key ? $emit('onClick', option.key) : () => {}
      "
      :key="option.key"
      v-for="option in options"
      :class="[
        'text-xs min-w-[130px] w-full py-2 text-center rounded-full px-5 transition-all',
        {
          'text-floatingPanel-text cursor-pointer hover:bg-floatingPanel-button-hover-background':
            activeKey !== option.key,
          'bg-floatingPanel-button-active-background hover:bg-floatingPanel-button-active-background text-floatingPanel-button-active-text font-semibold cursor-default':
            activeKey === option.key,
        },
      ]"
    >
      {{ option.label }}
    </div>
  </div>
</template>
