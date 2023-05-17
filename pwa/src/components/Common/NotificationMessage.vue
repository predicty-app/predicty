<script setup lang="ts">
import { watch } from "vue";

type PropsType = {
  modelValue: boolean;
  type?: "success" | "error";
  message: string;
};

const props = withDefaults(defineProps<PropsType>(), {
  type: "success"
});

const emit = defineEmits<{
  (e: "update:modelValue", value: boolean): void;
}>();

watch(
  () => props.modelValue,
  () => {
    if (props.modelValue) {
      setTimeout(() => emit("update:modelValue", false), 2500);
    }
  }
);
</script>

<template>
  <div
    v-if="modelValue"
    :class="[
      'animate-fade-in text-xs z-[9999] flex flex-col shadow border-solid p-3 rounded-[10px] shadow justify-center gap-y-0.5 border max-w-md w-full text-center fixed top-5 left-0 right-0 m-auto',
      {
        'text-basic-white  bg-green-400': type === 'success',
        'text-basic-white bg-red-100': type === 'error'
      }
    ]"
  >
    {{ message }}
  </div>
</template>
