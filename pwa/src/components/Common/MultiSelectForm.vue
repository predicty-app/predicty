<script setup lang="ts">
import { ref, watch, computed } from "vue";
import { useI18n } from "vue-i18n";

type OptionsType = {
  label: string;
  key: string;
  icon?: string;
};

type PropsType = {
  modelValue: string[];
  options: OptionsType[];
  minOptions?: number;
  position?: "top" | "bottom";
};

const props = withDefaults(defineProps<PropsType>(), {
  position: "bottom",
  minOptions: 1
});

const { t } = useI18n();

const emit = defineEmits<{
  (e: "update:modelValue", value: string[]): void;
}>();

const selectedElements = ref<string[]>(props.modelValue);
const isSelectOpened = ref<boolean>(false);
const isSelectionDisabled = computed<boolean>(
  () => selectedElements.value.length === props.minOptions
);

/**
 * Function to handle select element.
 * @param {string} item
 * @patam {boolean} canSelect
 */
function handleSelectElement(item: string, canSelect: boolean) {
  if (!canSelect) {
    if (isSelectionDisabled.value) {
      return;
    }

    handleRemoveElement(item);
    return;
  }

  selectedElements.value.push(item);
  emit("update:modelValue", selectedElements.value);
}

/**
 * Function to handle remove element.
 * @param {string} item
 */
function handleRemoveElement(item: string) {
  selectedElements.value = selectedElements.value.filter(
    (element) => element != item
  );
  emit("update:modelValue", selectedElements.value);
}

watch(
  () => props.modelValue,
  () => {
    selectedElements.value = props.modelValue;
  }
);
</script>

<template>
  <div class="relative" tabindex="0" @focusout="isSelectOpened = false">
    <div
      class="text-select-input-text bg-select-input-background border-select-input-border border py-2 px-3 text-xs rounded relative cursor-pointer"
      @click="isSelectOpened = !isSelectOpened"
    >
      <span
        class="text-select-input-placeholder"
        v-if="modelValue.length === 0"
      >
        {{ t("components.common.multiselect.placeholder") }}
      </span>
      <span v-if="modelValue.length > 0" class="font-semibold">
        {{
          t("components.common.multiselect.label", {
            count: modelValue.length
          })
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
              (isSelectOpened && position === 'top')
          }
        ]"
      />
    </div>
    <div
      v-if="isSelectOpened"
      :class="[
        'absolute left-0 w-full z-10 animate-fade-in shadow-sm bg-select-input-background border-select-overlayer-border border rounded',
        {
          'bottom-[110%]': position === 'top',
          'top-[110%]': position === 'bottom'
        }
      ]"
    >
      <div
        :class="[
          'p-2 transition-all flex items-center justify-between hover:bg-multiselect-element-hover-background',
          {
            'cursor-pointer':
              !selectedElements.find((element) => element === item.key) &&
              !isSelectionDisabled,
            'bg-multiselect-element-disabled-background hover:bg-multiselect-element-disabled-background grayscale':
              isSelectionDisabled &&
              selectedElements.find((element) => element === item.key)
          }
        ]"
        :key="`item_${item.key}`"
        v-for="item in options"
        @click="
          handleSelectElement(
            item.key,
            !selectedElements.find((element) => element === item.key)
          )
        "
      >
        <div
          class="flex items-center gap-x-[5px] text-xs font-semibold text-multiselect-lable-color"
        >
          <img v-if="item.icon" :src="item.icon" class="w-4" />
          {{ item.label }}
        </div>
        <div
          v-if="selectedElements.find((element) => element === item.key)"
          class="animate-fade-in rounded-full w-3 h-3 bg-multiselect-icon-background flex items-center justify-center"
        >
          <IconSvg
            name="check"
            class-name="w-[6px] h-2 fill-multiselect-icon-color"
          />
        </div>
      </div>
    </div>
  </div>
</template>
