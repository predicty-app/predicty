<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";

const globalStore = useGlobalStore();
</script>

<template>
  <slot name="header" />
  <div class="h-calc select-none grid grid-cols-[336px_auto]">
    <div
      class="h-calc select-none grid grid-cols-[auto] grid-rows-[320px_80px_auto]"
    >
      <div>
        <slot name="chart-legend" />
      </div>
      <div>
        <slot name="providers-list" />
      </div>
      <ScrollbarPanel :scroll-y="globalStore.scrollParams.y">
        <slot name="ads-campaigns" />
      </ScrollbarPanel>
    </div>
    <div
      class="h-calc select-none grid grid-cols-[auto] grid-rows-[360px_auto]"
    >
      <ScrollbarPanel :scroll-x="globalStore.scrollParams.x">
        <slot name="chart" />
      </ScrollbarPanel>
      <ScrollbarPanel
        @onChange="globalStore.handleChangeScrollParams"
        :is-vertical-scroll-visible="true"
      >
        <slot name="ads-chart" />
      </ScrollbarPanel>
    </div>
  </div>
</template>
