<script setup lang="ts">
type PropsType = {
  position?: "top" | "bottom" | "left" | "right";
  message: string;
  isActive?: boolean;
};

withDefaults(defineProps<PropsType>(), {
  position: "right",
  isActive: true
});
</script>

<template>
  <div :class="['tooltip-message relative']">
    <slot />
    <div
      v-if="isActive"
      :class="[
        'tooltip-message__overlayer absolute whitespace-normal w-full min-w-fit text-xs p-2 rounded bg-gray-400/90 shadow text-basic-white hidden',
        {
          'bottom-[105%] left-1/2 translate-x-[-50%]': position === 'top',
          'top-[105%]  left-1/2 translate-x-[-50%]': position === 'bottom',
          'right-[105%] top-1/2 translate-y-[-50%]': position === 'right',
          'left-[105%] top-1/2 translate-y-[-50%]': position === 'left'
        }
      ]"
    >
      {{ message }}
    </div>
  </div>
</template>

<style scoped lang="scss">
.tooltip-message {
  &:hover {
    .tooltip-message__overlayer {
      @apply block;
    }
  }
}
</style>
