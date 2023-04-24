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
        'bg-button-default border border-button-border hover:bg-button-hover-default active:bg-button-active-default':
          type === 'default',
        'bg-button-success text-text-white hover:bg-button-hover-success active:bg-button-active-success':
          type === 'success',
        'cursor-not-allowed text-text-white bg-button-disabled border-button-disabled hover:bg-button-disabled active:bg-button-disabled':
          type === 'disabled'
      }
    ]"
  >
    <slot />
  </button>
</template>
