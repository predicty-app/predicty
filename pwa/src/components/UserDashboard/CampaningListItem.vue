<script setup lang="ts">
type PropsType = {
  color?: string;
  header?: string;
};

withDefaults(defineProps<PropsType>(), {
  color: "#d4d4d4"
});
</script>

<template>
  <div
    class="flex flex-col px-3 gap-y-2 border-l-dynamic border-l-[3px] pb-5"
    :style="{ '--border': color }"
  >
    <h3
      v-if="header"
      class="font-bold text-base text-dynamic"
      :style="{ '--color': color }"
    >
      <template v-if="header.length > 25">
        <TooltipMessage position="top" :message="header">
          {{ header.slice(0, 25) }}...
        </TooltipMessage>
      </template>
      <template v-else>
        {{ header }}
      </template>
    </h3>
    <div class="font-semibold text-xs text-gray-600">
      <slot />
    </div>
  </div>
</template>
