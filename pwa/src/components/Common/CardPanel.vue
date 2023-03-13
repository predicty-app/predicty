<script setup lang="ts">
type PropsType = {
  type?: "success" | "default";
};

withDefaults(defineProps<PropsType>(), {
  type: "default"
});
</script>

<template>
  <div
    data-testid="card-panel"
    :data-type="type"
    :class="[
      'border p-6 rounded-[10px] hover:shadow-md transition-all',
      {
        ' bg-card-background-success border-card-border-success text-card-text-success':
          type === 'success',
        'bg-card-background-default border-card-border-default text-card-text-default':
          type === 'default'
      }
    ]"
  >
    <slot />
  </div>
</template>
