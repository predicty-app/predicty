<script setup lang="ts">
import { ref, computed } from "vue";
import { useGlobalStore } from "@/stores/global";
import { hLightenDarkenColor } from "@/helpers/utils";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { AdsType, AdSetsType } from "@/stores/userDashboard";

type PropsType = {
  end: number;
  start: number;
  color?: string;
  isVisible?: boolean;
  type?: "ad";
  element: AdsType | AdSetsType;
};

const props = withDefaults(defineProps<PropsType>(), {
  type: "ad",
  isVisible: true,
  color: "#19BE34B2"
});

const userStore = useUserDashboardStore();
const isSelectedElement = computed<boolean>(() =>
  userStore.selectedAdsList.ads.includes(props.element.uid)
);
const timelineItemInstance = ref<HTMLDivElement | null>(null);

const parseStart = computed<number>(() =>
  props.start > props.end ? props.end : props.start
);
const parseEnd = computed<number>(() =>
  props.start < props.end ? props.end : props.start
);

const isElementAssignCheckedCollection = computed<boolean>(() => {
  return (
    userStore.selectedAdsList.ads.length > 0 &&
    !userStore.selectedAdsList.ads.includes(props.element.uid)
  );
});

const currentColor = computed<string>(() =>
  hLightenDarkenColor(
    userStore.hiddenAds.includes(props.element.uid) ? "#d1d1d1" : props.color,
    isSelectedElement.value ? -30 : 0
  )
);

const emit = defineEmits<{
  (e: "collectionSelected", value: AdSetsType): void;
}>();

/**
 * Function to handle select ad.
 */
function handleToogleSelectAd() {
  console.log('Collection item select.')
}
</script>

<template>
  <div ref="timelineItemInstance" :class="[
    `col-start-dynamic col-end-dynamic p-[1.5px] rounded-[6px] h-fit my-auto`,
    {
      'opacity-50': isElementAssignCheckedCollection
    }
  ]" :style="{
  '--start': parseStart,
  '--end': parseEnd - parseStart < 2 ? parseEnd + 5 : parseEnd,
  '--color': currentColor
}">
    <div :class="[
      'p-2 text-xs overflow-hidden cursor-pointer rounded-[5px] shadow-sm text-text-white font-semibold bg-timeline-item-background',
      {
        'shadow-lg shadow-timeline-shadow': isSelectedElement
      }
    ]" @click="handleToogleSelectAd" :style="{ '--color': currentColor }">
      <div class="pr-4 flex gap-x-1 items-center">
        <CheckboxForm :color="currentColor" :is-checked="isSelectedElement" />
      </div>
    </div>
  </div>
</template>
