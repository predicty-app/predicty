<script setup lang="ts">
type PropsType = {
  modelValue: boolean;
  width?: number;
  isConversation?: boolean;
  x?: number;
  y?: number;
};

defineProps<PropsType>();
</script>

<template>
  <div
    class="flex fixed z-20 w-full h-full top-0 left-0 items-center justify-center bg-modalWindow-mask-background animate-fade-in"
    :class="{ 'border-2 border-red p-3': isConversation }"
    v-if="modelValue"
  >
    <div
      :class="[{ fixed: x && y }, width ? `w-[${width}px]` : 'w-full max-w-xl']"
      :style="[
        width
          ? {
              width: `${width}px`
            }
          : '',
        x && y
          ? {
              top: `${y}px`,
              left: `${x}px`
            }
          : ''
      ]"
    >
      <CardPanel
        :type="isConversation ? 'comments' : 'default'"
        class="scroll-bar overflow-x-hidden overflow-y-auto"
        :style="[
          y
            ? {
                'max-height': `calc(100vh - ${y}px - 10px)`,
                'overflow-y': 'auto'
              }
            : ''
        ]"
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
