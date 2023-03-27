<script setup lang="ts">
import { ref, watch } from 'vue';
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";

const globalStore = useGlobalStore();
const userDashboardStore = useUserDashboardStore();
const dummyDragInstance = ref<HTMLDivElement | null>(null);

/**
 * Function to handle mouse move.
 * @param {MouseEvent} e 
 */
function handleMouseMove(e: MouseEvent) {
  dummyDragInstance.value.style.left = `${e.clientX + 5}px`;
  dummyDragInstance.value.style.top = `${e.clientY + 5}px`
}

watch(() => globalStore.isActiveActionDrag, () => {
  if (globalStore.isActiveActionDrag && userDashboardStore.selectedAdsList.ads.length > 0) {
    window.onmousemove = handleMouseMove
  } else {
    window.onmousemove = null;
  }
})
</script>

<template>
  <div v-if="globalStore.isActiveActionDrag && userDashboardStore.selectedAdsList.ads.length > 0" ref="dummyDragInstance"
    class="animate-fade-in opacity-70 fixed z-[1000] rounded-full h-10 w-10 flex items-center justify-center bg-floatingPanel-background text-floatingPanel-text font-bold text-base">
    {{ userDashboardStore.selectedAdsList.ads.length }}
  </div>
</template>