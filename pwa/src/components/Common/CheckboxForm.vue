<script setup lang="ts">
import { ref, onMounted } from "vue";

type PropsType = {
  isChecked: boolean
  color?: string
};

withDefaults(defineProps<PropsType>(), {
  isChecked: false,
  color: "#56CE6B"
});

const emit = defineEmits<{
  (e: "onChange", value: boolean): void;
}>();

</script>

<template>
  <div @click="emit('onChange', !isChecked)" :class="['w-[17px] h-[17px] flex  items-center justify-center rounded-[3px] border-checkboxForm-border opacity-90 border-2 cursor-pointer', {
    'bg-checkboxForm-active-background': isChecked
  }]">
    <div class="w-[9px] h-[2px] bg-checkboxForm-active-color" :style="{ '--color': color }"></div>
  </div>
</template>
