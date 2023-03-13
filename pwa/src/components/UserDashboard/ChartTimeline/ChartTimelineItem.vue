<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { ref, onMounted, watch, computed } from "vue";
import { hLightenDarkenColor } from "@/helpers/utils";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { AdsType, AdsCollection } from "@/stores/userDashboard";

type PropsType = {
  end: number;
  start: number;
  color?: string;
  isVisible?: boolean;
  campaingUid?: string;
  type?: "ad" | "collection";
  element: AdsType | AdsCollection;
};

const props = withDefaults(defineProps<PropsType>(), {
  type: "ad",
  isVisible: true,
  color: "#19BE34B2",
});

const globalStore = useGlobalStore();
const userStore = useUserDashboardStore();
const isElementVisible = ref<boolean>(true);
const boundingBoxElement = ref<DOMRect | null>(null);
const timelineItemInstance = ref<HTMLDivElement | null>(null);
const isSelectedElement = computed<boolean>(() =>
  userStore.selectedAdsList.ads.includes(props.element.uid)
);

const isElementAssignCheckedCollection = computed<boolean>(() => {
  return (
    userStore.selectedAdsList.campaignUid !== props.campaingUid &&
    userStore.selectedAdsList.ads.length > 0
  );
});

const currentColor = computed<string>(() =>
  hLightenDarkenColor(
    userStore.hiddenAds.includes(props.element.uid) ? "#d1d1d1" : props.color,
    isSelectedElement.value ? -30 : 0
  )
);

onMounted(() => {
  boundingBoxElement.value = timelineItemInstance.value.getBoundingClientRect();
  handleVisibleElement();
});

watch(
  () => [globalStore.scrollParams, globalStore.currentScale],
  () => {
    handleVisibleElement();
  }
);

/**
 * Function to handle visible element.
 */
function handleVisibleElement() {
  if (!globalStore.scrollTimeline || !props.isVisible) {
    isElementVisible.value = false;
    return;
  }

  const currentLeftPosition =
    boundingBoxElement.value.left -
    globalStore.scrollTimeline.getBoundingClientRect().left +
    globalStore.scrollParams.x;
  const currentRightPosition =
    currentLeftPosition + boundingBoxElement.value.width;

  isElementVisible.value =
    currentLeftPosition <
      globalStore.scrollParams.x +
        globalStore.scrollTimeline.getBoundingClientRect().width &&
    currentRightPosition > globalStore.scrollParams.x;

  if (!props.isVisible) {
    isElementVisible.value = false;
  }
}

/**
 * Function to handle select ad.
 */
function handleToogleSelectAd() {
  if (props.type !== "ad") {
    return;
  }

  if (
    !(
      userStore.selectedAdsList.campaignUid !== props.campaingUid &&
      userStore.selectedAdsList.ads.length > 0
    )
  ) {
    userStore.toogleAssignAdsAction(props.campaingUid, props.element.uid);
  } else {
    userStore.selectedAdsList.ads = [];
    userStore.selectedAdsList.campaignUid = null;

    userStore.toogleAssignAdsAction(props.campaingUid, props.element.uid);
  }
}
</script>

<template>
  <div
    ref="timelineItemInstance"
    v-if="isElementVisible"
    :class="[
      `col-start-dynamic col-end-dynamic p-[1.5px] rounded-[6px] h-fit`,
      {
        'border-[2px] border-timeline-item-border': type === 'collection',
        'opacity-50': isElementAssignCheckedCollection,
      },
    ]"
    :style="{ '--start': start, '--end': end, '--color': currentColor }"
  >
    <div
      :class="[
        'p-2 text-xs cursor-pointer rounded-[5px] shadow-sm flex gap-x-1 items-center text-text-white font-semibold bg-timeline-item-background',
        {
          'shadow-lg shadow-timeline-shadow': isSelectedElement,
        },
      ]"
      @click="handleToogleSelectAd"
      :style="{ '--color': currentColor }"
    >
      <CheckboxForm
        v-if="type === 'ad'"
        :color="currentColor"
        :is-checked="isSelectedElement"
      />
      <IconSvg v-if="type === 'collection'" name="bars" class-name="w-4 h-4" />
      <div
        v-if="type === 'collection'"
        class="rounded font-semibold text-xs w-[14px] py-[1px] bg-timeline-collection-count flex items-center justify-center"
      >
        {{ (element as AdsCollection).ads.length }}
      </div>
      {{ element.name }}
    </div>
  </div>
</template>
