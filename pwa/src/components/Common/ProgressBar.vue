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
  <div class="flex items-center w-full relative">
    <div
      class="h-2 w-full absolute top-0 bottom-0 m-auto bg-progress-default"
    ></div>
    <template :key="`step_${step}`" v-for="step in countSteps">
      <div
        :class="[
          'w-[18px] h-[18px] rounded-full relative z-20',
          {
            'bg-progress-active': activeStep > step - 1,
            'bg-progress-default': activeStep < step,
          },
        ]"
      ></div>
      <div
        v-if="step < countSteps"
        :class="[
          'flex-auto h-2 z-20',
          {
            'bg-progress-active': activeStep > step,
          },
        ]"
      ></div>
    </template>
  </div>
</template>
