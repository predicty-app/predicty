<script setup lang="ts">
type PropsType = {
  type?: "success" | "default" | "disabled";
};

withDefaults(defineProps<PropsType>(), {
  type: "default"
});
</script>

<template>
  <button
    data-testid="button-form"
    :data-type="type"
    :disabled="type === 'disabled'"
    :class="[
      'py-[18px] rounded-[10px] w-full text-base transition-all',
      {
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
