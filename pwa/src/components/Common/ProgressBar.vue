<script setup lang="ts">
type PropsType = {
  countSteps: number;
  activeStep?: number;
};

withDefaults(defineProps<PropsType>(), {
  activeStep: -1,
});
</script>

<template>
  <div class="flex items-center w-full">
    <div
      data-testid="progress-bar-lines"
      :data-active="activeStep > step"
      :key="`step_${step}`"
      v-for="step in countSteps"
      :class="[
        'flex-auto h-[6px]',
        {
          'bg-progress-active': activeStep >= step,
        },
      ]"
    ></div>
  </div>
</template>
