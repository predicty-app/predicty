<script setup lang="ts">
type PropsType = {
  modelValue: boolean;
  width?: number;
};

defineProps<PropsType>();
</script>

<template>
  <div
    class="flex fixed z-[100] w-full h-full top-0 left-0 items-center justify-center bg-modalWindow-mask-background animate-fade-in"
    v-if="modelValue"
  >
    <div
      :class="[
        'max-w-xl',
        {
          'w-full': !width,
          'w-dynamic': width
        }
      ]"
      :style="{ '--height': `${width}px` }"
    >
      <CardPanel
        type="default"
        class="scroll-bar overflow-x-hidden overflow-y-auto"
      >
        <slot />
      </CardPanel>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.scroll-bar {
  &::-webkit-scrollbar-track {
    border-radius: 10px;
    margin: 0.75rem;
  }
}
</style>
