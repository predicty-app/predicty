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
  position: "bottom",
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
  <div class="relative" tabindex="0" @focusout="isSelectOpened = false">
    <div
      class="text-select-input-text bg-select-input-background border-select-input-border border py-2 px-3 text-xs rounded relative cursor-pointer"
      @click="isSelectOpened = !isSelectOpened"
    >
      <span
        class="text-select-input-placeholder"
        v-if="!modelValue && placeholder"
      >
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
          'fill-select-input-text w-2 h-2 absolute top-0 bottom-0 right-3 m-auto transition-all',
          {
            'rotate-90':
              (isSelectOpened && position === 'bottom') ||
              (!isSelectOpened && position === 'top'),
            'rotate-[-90deg]':
              (!isSelectOpened && position === 'bottom') ||
              (isSelectOpened && position === 'top'),
          },
        ]"
      />
    </div>
    <div
      v-if="isSelectOpened"
      :class="[
        'absolute left-0 w-full animate-fade-in shadow-sm bg-select-input-background border-select-overlayer-border border rounded',
        {
          'bottom-[110%]': position === 'top',
          'top-[110%]': position === 'bottom',
        },
      ]"
    >
      <div
        :class="[
          'p-2 font-normal text-xs transition-all',
          {
            ' text-select-options-default-text bg-select-options-default-background hover:bg-select-options-default-hover cursor-pointer':
              option.key !== modelValue,
            '  bg-select-options-active-background cursor-default':
              option.key === modelValue,
          },
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
