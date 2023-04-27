<script setup lang="ts">
type PropsType = {
  type?: "success" | "default" | "comments";
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
      'border rounded-[10px] hover:shadow-md transition-all',
      {
        ' bg-card-background-success border-card-border-success text-card-text-success p-6':
          type === 'success',
        'bg-card-background-default border-card-border-default text-card-text-default p-6':
          type === 'default',
        'bg-card-background-default p-3 pb-0 border-2 border-comments-red':
          type === 'comments'
      }
    ]"
  >
    <slot />
  </div>
</template>
