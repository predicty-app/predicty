<script setup lang="ts">
import { defineAsyncComponent, computed } from "vue";

type PropsType = {
  name: string;
  className?: string;
};

const props = withDefaults(defineProps<PropsType>(), {
  className: "w-7 h-7",
});
const iconComponent = computed(() => {
  const parsed = `${props.name.charAt(0).toUpperCase()}${props.name.slice(1)}`;
  return defineAsyncComponent(
    () => import(`../../assets/icons/Icon${parsed}.vue`)
  );
});
</script>

<template>
  <component :class="className" :is="iconComponent" />
</template>
