<script setup lang="ts">
type PropsType = {
  type?: "default" | "success" | "primary";
};

withDefaults(defineProps<PropsType>(), {
  type: "default"
});
</script>

<template>
  <div
    data-testid="tag-pin"
    :data-type="type"
    :class="[
      'px-2 rounded text-[10px] border justify-center flex  gap-x-1 items-center',
      {
        'bg-blue-100 border-blue-100 text-gray-1200': type === 'default',
        'bg-tag-background border-tag-border text-tag-text': type === 'success',
        ' bg-green-200 text-basic-white border border-solid border-green-300':
          type === 'primary'
      }
    ]"
  >
    <IconSvg
      v-if="type === 'success'"
      class-name="w-3 h-3 fill-green-200"
      name="check"
    />
    <slot />
  </div>
</template>
