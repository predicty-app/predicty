<script setup lang="ts">
import { watch } from "vue";

type PropsType = {
  modelValue: boolean;
  type?: "success" | "info" | "warning" | "error";
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
      'animate-fade-in text-xs z-50 flex flex-col shadow border-solid p-3 rounded shadow justify-center gap-y-0.5 border max-w-md w-full text-center fixed top-5 left-0 right-0 m-auto',
      {
        'text-notification-text bg-notification-success': type === 'success',
        'text-notification-text bg-notification-error': type === 'error',
        'text-notification-text bg-notification-warning': type === 'warning',
        'text-notification-text bg-notification-info': type === 'info'
      }
    ]"
  >
    {{ message }}
  </div>
</template>
