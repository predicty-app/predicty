<script setup lang="ts">
import { defineAsyncComponent, computed } from "vue";

type PropsType = {
  name: string
}

const props = defineProps<PropsType>()
const iconComponent = computed(() => {
  const parsed = `${props.name.charAt(0).toUpperCase()}${props.name.slice(1)}`
  return defineAsyncComponent(() => import(`../../assets/icons/Icon${parsed}.vue`));
})

</script>

<template>
  <component class="w-7 h-7" :is="iconComponent" />
</template>
