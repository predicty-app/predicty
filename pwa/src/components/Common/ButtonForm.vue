<script setup lang="ts">
type PropsType = {
  type?: "success" | "default" | "disabled";
  isSmall?: boolean;
};

withDefaults(defineProps<PropsType>(), {
  type: "default",
  isSmall: false
});
</script>

<template>
  <button
    data-testid="button-form"
    :data-type="type"
    :disabled="type === 'disabled'"
    :class="[
      'rounded-[10px] w-full text-base transition-all',
      {
        'text-xs px-3 py-2 h-[31px]': isSmall,
        'py-[18px]': !isSmall,
        'bg-gray-100 border border-solid border-blue-100 hover:bg-blue-100 active:bg-blue-100 ':
          type === 'default',
        'bg-green-200 text-basic-white hover:bg-green-400 active:bg-green-500 border-0 border-green-200 outline-green-200':
          type === 'success',
        'cursor-not-allowed text-basic-white bg-gray-700 border-solid outline-none border-gray-700 hover:bg-gray-700 active:bg-gray-700':
          type === 'disabled'
      }
    ]"
  >
    <slot />
  </button>
</template>
