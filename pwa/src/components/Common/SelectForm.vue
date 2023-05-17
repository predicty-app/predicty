<script setup lang="ts">
import { ref } from "vue";

type OptionsType = {
  label: string;
  key: string | number;
};

type PropsType = {
  modelValue: string;
  options: OptionsType[];
  placeholder?: string;
  position?: "top" | "bottom";
};

withDefaults(defineProps<PropsType>(), {
  position: "bottom"
});

const emit = defineEmits<{
  (e: "update:modelValue", value: string | number): void;
}>();

const isSelectOpened = ref<boolean>(false);

/**
 * Function to handle select element.
 * @param item
 */
function handleSelectElement(item: string | number) {
  emit("update:modelValue", item);
  isSelectOpened.value = false;
}
</script>

<template>
  <div
    class="select-form relative"
    tabindex="0"
    @focusout="isSelectOpened = false"
  >
    <div
      class="select-form-content text-gray-900 bg-basic-white border border-solid outline-none border-blue-100 py-2 px-3 text-xs rounded relative cursor-pointer"
      @click="isSelectOpened = !isSelectOpened"
    >
      <span class="text-gray-900-text" v-if="!modelValue && placeholder">
        {{ placeholder }}
      </span>
      <span v-if="modelValue" class="font-semibold">
        {{
          options.find((option: OptionsType) => option.key === modelValue).label
        }}
      </span>
      <IconSvg
        name="arrowright"
        :class-name="[
          'fill-gray-900 w-2 h-2 absolute top-0 bottom-0 right-3 m-auto transition-all',
          {
            'rotate-90':
              (isSelectOpened && position === 'bottom') ||
              (!isSelectOpened && position === 'top'),
            'rotate-[-90deg]':
              (!isSelectOpened && position === 'bottom') ||
              (isSelectOpened && position === 'top')
          }
        ]"
      />
    </div>
    <div
      v-if="isSelectOpened"
      :class="[
        'absolute z-50 left-0 w-full animate-fade-in shadow-sm bg-basic-white border-blue-100 border rounded',
        {
          'bottom-[110%]': position === 'top',
          'top-[110%]': position === 'bottom'
        }
      ]"
    >
      <div
        :class="[
          'p-2 font-normal text-xs transition-all',
          {
            'text-gray-1200 bg-basic-white hover:bg-green-100 cursor-pointer':
              option.key !== modelValue,
            'bg-green-100 cursor-default': option.key === modelValue
          }
        ]"
        :key="option.key"
        v-for="option in options"
        @click="handleSelectElement(option.key)"
      >
        {{ option.label }}
      </div>
    </div>
  </div>
</template>
