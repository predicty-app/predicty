<script setup lang="ts">
type PropsType = {
  modelValue: boolean;
};

defineProps<PropsType>();
</script>

<template>
  <div
    class="flex fixed z-20 w-full h-full top-0 left-0 items-center justify-center bg-modalWindow-mask-background animate-fade-in"
    v-if="modelValue"
  >
    <div class="max-w-xl w-full">
      <CardPanel type="default">
        <slot />
      </CardPanel>
    </div>
  </div>
</template>
