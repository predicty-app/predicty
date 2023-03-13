<script setup lang="ts">
type PropsType = {
  color?: string;
  header?: string;
};

withDefaults(defineProps<PropsType>(), {
  color: "#d4d4d4",
});
</script>

<template>
  <div
    class="flex flex-col px-3 gap-y-2 border-l-campaign-item-border border-l-[3px] pb-5"
    :style="{ '--color': color }"
  >
    <h3
      v-if="header"
      class="font-bold text-base text-campaign-item-header-color"
      :style="{ '--color': color }"
    >
      {{ header }}
    </h3>
    <div class="font-semibold text-xs text-campaign-item-content-color">
      <slot />
    </div>
  </div>
</template>
