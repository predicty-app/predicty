<script setup lang="ts">
import { vMaska } from "maska";

type PropsType = {
  mask?: string;
  icon?: string;
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
      data-testid="input-form-label"
      v-if="label"
      class="text-xs mb-1 block ml-1"
    >
      <span
        data-testid="input-form-required"
        class="text-red-100"
        v-if="required"
        >*</span
      >
      {{ label }}
    </label>
    <div class="relative">
      <IconSvg
        class="absolute left-6 m-auto top-0 bottom-0"
        v-if="icon"
        :name="icon"
      />
      <input
        type="text"
        v-maska
        data-testid="input-form-input"
        v-bind="mask ? { 'data-maska': mask } : ''"
        :value="modelValue"
        @input="changeValue"
        :class="[
          'w-full p-4 font-normal bg-basic-white text-gray-900 text-base border border-solid outline-none border-blue-100 rounded-[10px] transition-all',
          {
            ' pl-16': icon
          }
        ]"
        :placeholder="placeholder"
      />
    </div>
    <span
      v-if="errorMessage"
      data-testid="input-form-error"
      class="text-red-100 text-xs block mt-1 ml-1"
    >
      {{ errorMessage }}
    </span>
  </div>
</template>
