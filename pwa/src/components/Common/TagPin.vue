<script setup lang="ts">
type PropsType = {
  type?: 'default' | 'success' | 'primary'
}

withDefaults(defineProps<PropsType>(), {
  type: 'default',
  required: false
})

</script>

<template>
  <div :class="['px-2 rounded text-[10px] border justify-center flex  gap-x-1 items-center', {
    'bg-tag-background-default border-tag-border-default text-tag-text-default': type === 'default',
    'bg-tag-background-success border-tag-border-success text-tag-text-success': type === 'success',
    'bg-tag-background-primary border-tag-border-primary text-tag-text-primary': type === 'primary',
  }]">
    <IconSvg v-if="type === 'success'" class="w-3 h-3" name="check"/>
    <slot />
  </div>
</template>