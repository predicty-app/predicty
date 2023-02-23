<script setup lang="ts">
type PropsType = {
  type?: 'success' | 'default'
}

withDefaults(defineProps<PropsType>(), {
  type: 'default'
})
</script>

<template>
  <button :class="['py-[18px] rounded-[10px] w-full text-base transition-all', {
    'bg-button-default hover:bg-button-hover-default active:bg-button-active-default': type === 'default',
    'bg-button-success text-text-white hover:bg-button-hover-success active:bg-button-active-success': type === 'success'
  }]">
    <slot />
  </button>
</template>