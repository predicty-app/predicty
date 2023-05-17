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
        'bg-green-200 border-green-200 border border-solid text-gray-1200 p-6':
          type === 'success',
        'bg-basic-white border-blue-100 border border-solid text-gray-1200 p-6':
          type === 'default'
      }
    ]"
  >
    <slot />
  </div>
</template>
