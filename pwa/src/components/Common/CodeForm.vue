<script setup lang="ts">
import { reactive, watch, ref } from "vue";

type PropsType = {
  label?: string;
  required?: boolean;
  modelValue: string;
  errorMessage?: string;
};

const props = withDefaults(defineProps<PropsType>(), {
  required: false
});

type InputCodeType = {
  number_one: string;
  number_two: string;
  number_three: string;
  number_four: string;
  number_five: string;
  number_six: string;
};

const modelCodeNumbers = reactive<InputCodeType>({
  number_one: "0",
  number_two: "0",
  number_three: "0",
  number_four: "0",
  number_five: "0",
  number_six: "0"
});

const currentSelectedElement = ref<number>(0);
const currentModelValue = ref<string>(props.modelValue);
const elementCodeInstance = ref<HTMLDivElement | null>(null);

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void;
}>();

/**
 * Function for focus current element.
 * @param {number} indexElement
 */
function handleFocusElement(indexElement: number) {
  currentSelectedElement.value = indexElement;

  for (let index = 0; index < 6; index++) {
    if (indexElement === index) {
      const inputElement =
        elementCodeInstance.value.querySelectorAll("input")[indexElement];
      inputElement.setSelectionRange(0, 1);
    }
  }
}

watch(
  () => props.modelValue,
  () => (currentModelValue.value = props.modelValue)
);

[
  "number_one",
  "number_two",
  "number_three",
  "number_four",
  "number_five",
  "number_six"
].forEach((item, index) => {
  watch(
    () => modelCodeNumbers[item],
    () => {
      currentSelectedElement.value = index + 1;
      const inputElement =
        elementCodeInstance.value.querySelectorAll("input")[index + 1];

      if (index < 6) {
        const value = currentModelValue.value.split("");
        value[index] = modelCodeNumbers[item];
        emit("update:modelValue", value.slice(0, 6).join(""));
      }

      if (inputElement) {
        inputElement.focus();
        inputElement.setSelectionRange(0, 1);
      }
    }
  );
});
</script>

<template>
  <div>
    <label v-if="label" class="text-xs mb-1 block ml-1">
      <span class="text-text-error" v-if="required">*</span>
      {{ label }}
    </label>
    <div ref="elementCodeInstance" class="flex gap-x-2 items-center">
      <InputForm
        ref="elementInputOne"
        class="w-[45px]"
        @focusin="handleFocusElement(0)"
        v-model="modelCodeNumbers.number_one"
        mask="#"
      />
      <InputForm
        ref="elementInputTwo"
        class="w-[45px]"
        @focusin="handleFocusElement(1)"
        v-model="modelCodeNumbers.number_two"
        mask="#"
      />
      <InputForm
        ref="elementInputThree"
        class="w-[45px]"
        @focusin="handleFocusElement(2)"
        v-model="modelCodeNumbers.number_three"
        mask="#"
      />
      <div class="w-[10px] h-[2px] bg-codeForm-dash-background"></div>
      <InputForm
        ref="elementInputFour"
        class="w-[45px]"
        @focusin="handleFocusElement(3)"
        v-model="modelCodeNumbers.number_four"
        mask="#"
      />
      <InputForm
        ref="elementInputFive"
        class="w-[45px]"
        @focusin="handleFocusElement(4)"
        v-model="modelCodeNumbers.number_five"
        mask="#"
      />
      <InputForm
        ref="elementInputSix"
        class="w-[45px]"
        @focusin="handleFocusElement(5)"
        v-model="modelCodeNumbers.number_six"
        mask="#"
      />
    </div>
    <span
      v-if="errorMessage"
      data-testid="input-form-error"
      class="text-text-error text-xs block mt-1 ml-1"
    >
      {{ errorMessage }}
    </span>
  </div>
</template>
