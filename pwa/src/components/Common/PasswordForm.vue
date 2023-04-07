<script setup lang="ts">
import { vMaska } from "maska";

type PropsType = {
  label?: string;
  type?: "default";
  required?: boolean;
  modelValue: string;
  placeholder?: string;
  errorMessage?: string;
};

withDefaults(defineProps<PropsType>(), {
  type: "default",
  required: false
});

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void;
  (e: "onChange", value: string): void;
}>();

/**
 * Function to catch input event.
 * @param {Event} event
 */
function changeValue(event: Event) {
  emit("update:modelValue", (event.target as HTMLInputElement).value);
  emit("onChange", (event.target as HTMLInputElement).value);
}
</script>

<template>
  <div>
    <label
      v-if="label"
      class="text-xs mb-1 block ml-1"
    >
      <span
        class="text-text-error"
        v-if="required"
        >*</span
      >
      {{ label }}
    </label>
    <input
      type="password"
      v-maska
      :value="modelValue"
      @input="changeValue"
      :class="[
        'w-full p-4 font-normal text-text-input text-base border border-slate-200 rounded-[10px] transition-all',
        {
          'border-default-border outline-default-outline': type === 'default'
        }
      ]"
      :placeholder="placeholder"
    />
    <span
      v-if="errorMessage"
      data-testid="input-form-error"
      class="text-text-error text-xs block mt-1 ml-1"
    >
      {{ errorMessage }}
    </span>
  </div>
</template>
