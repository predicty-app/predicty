<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { ref, onMounted, watch, computed } from "vue";
import { hLightenDarkenColor } from "@/helpers/utils";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { AdsType, AdSetsType } from "@/stores/userDashboard";

type PropsType = {
  end: number;
  start: number;
  color?: string;
  isVisible?: boolean;
  campaingUid?: string;
  type?: "ad" | "collection";
  element: AdsType | AdSetsType;
};

const props = withDefaults(defineProps<PropsType>(), {
  type: "ad",
  isVisible: true,
  color: "#19BE34B2"
});

const globalStore = useGlobalStore();
const userStore = useUserDashboardStore();
const isElementVisible = ref<boolean>(true);
const boundingBoxElement = ref<DOMRect | null>(null);
const timelineItemInstance = ref<HTMLDivElement | null>(null);
const isSelectedElement = computed<boolean>(() =>
  userStore.selectedAdsList.ads.includes(props.element.uid)
);
const tooltipPositionX = ref<string>("0px");
const tooltipPositionY = ref<string>("0px");
const isTooltipVisible = ref<boolean>(false);
const tooltTipReference = ref<HTMLDivElement | null>(null);

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

onMounted(() => {
  if (timelineItemInstance.value) {
    boundingBoxElement.value =
      timelineItemInstance.value.getBoundingClientRect();
    handleVisibleElement();
  }
});

watch(
  () => [globalStore.scrollParams, globalStore.currentScale],
  () => {
    handleVisibleElement();
  }
);

const emit = defineEmits<{
  (e: "collectionSelected", value: AdSetsType): void;
}>();

/**
 * Function to handle visible element.
 */
function handleVisibleElement() {
  // --------------------------------------------------------------------------
  // --> commented functionality at Stan's request -> remove virtualization <--
  // --------------------------------------------------------------------------

  // if (!globalStore.scrollTimeline || !props.isVisible) {
  //   isElementVisible.value = false;
  //   return;
  // }

  // const currentLeftPosition =
  //   boundingBoxElement.value.left -
  //   globalStore.scrollTimeline.getBoundingClientRect().left +
  //   globalStore.scrollParams.x;
  // const currentRightPosition =
  //   currentLeftPosition + boundingBoxElement.value.width;

  // isElementVisible.value =
  //   currentLeftPosition <
  //     globalStore.scrollParams.x +
  //       globalStore.scrollTimeline.getBoundingClientRect().width &&
  //   currentRightPosition > globalStore.scrollParams.x;

  if (!props.isVisible) {
    isElementVisible.value = false;
  }
}

/**
 * Function to handle select ad.
 */
function handleToogleSelectAd() {
  if (
    props.type !== "ad" ||
    !userStore.activeProviders.includes(props.element.dataProvider[0])
  ) {
    return;
  }
  emit("collectionSelected", null);
  userStore.toogleAssignAdsAction(props.campaingUid, props.element.uid);
}

/**
 * Function handle select collection.
 */
function handleSelectCollection() {
  userStore.selectedAdsList.ads = [];
  userStore.selectedAdsList.campaignUid = null;

  userStore.selectedCollection = null;

  emit("collectionSelected", props.element as AdSetsType);
}

function handleShowTooltipPosition(event: MouseEvent) {
  if (tooltTipReference.value) {
    tooltipPositionX.value = `${
      event.clientX - tooltTipReference.value.getBoundingClientRect().width / 2
    }px`;
    tooltipPositionY.value = `${
      event.clientY -
      tooltTipReference.value.getBoundingClientRect().height -
      20
    }px`;
  }
}
</script>

<template>
  <div
    ref="timelineItemInstance"
    v-if="isElementVisible && element.dataProvider"
    :class="[
      `col-start-dynamic col-end-dynamic p-[1.5px] rounded-[6px] h-fit my-auto transition-all`,
      {
        'border-[2px] border-timeline-item-border': type === 'collection',
        'opacity-50': isElementAssignCheckedCollection,
        'blur-[0.5px] opacity-20 grayscale cursor-default':
          !userStore.activeProviders.includes(element.dataProvider[0]) &&
          type === 'ad',
        'cursor-pointer':
          type === 'collection' ||
          (userStore.activeProviders.includes(element.dataProvider[0]) &&
            type === 'ad')
      }
    ]"
    v-on="
      type === 'collection' ? { click: () => handleSelectCollection() } : {}
    "
    :style="{
      '--start': parseStart,
      '--end': parseEnd - parseStart < 2 ? parseEnd + 5 : parseEnd,
      '--color': currentColor
    }"
  >
    <div
      :class="[
        'p-2 text-xs overflow-hidden relative rounded-[5px] shadow-sm text-text-white font-semibold bg-timeline-item-background',
        {
          'shadow-lg shadow-timeline-shadow': isSelectedElement
        }
      ]"
      @click="handleToogleSelectAd"
      @mouseenter="isTooltipVisible = true"
      @mouseleave="isTooltipVisible = false"
      @mousemove="handleShowTooltipPosition"
      :style="{ '--color': currentColor }"
    >
      <div
        ref="tooltTipReference"
        v-if="
          isTooltipVisible &&
          parseEnd - parseStart < 5 &&
          userStore.activeProviders.includes(element.dataProvider[0])
        "
        class="bg-popover-background drop-shadow-md rounded-xl z-[9999] text-chartBar-text fixed animate-fade-in text-center py-[10px] px-3 top-dynamic"
        :style="{ top: tooltipPositionY, left: tooltipPositionX }"
      >
        <IconSvg
          name="triangle"
          class-name="absolute w-3 h-3 bottom-[-9px] m-auto left-0 right-0"
        />
        {{ element.name }}
      </div>
      <div class="pr-4 flex gap-x-1 items-center overflow-hidden">
        <CheckboxForm
          v-if="type === 'ad'"
          :color="currentColor"
          :is-checked="isSelectedElement && !userStore.isDragAndDrop"
        />
        <svg
          v-if="type === 'collection'"
          class="min-w-[16px] w-[16px]"
          width="16"
          height="16"
          viewBox="0 0 16 16"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <rect x="1" y="1" width="7" height="3" rx="1" fill="white" />
          <rect y="11" width="12" height="3" rx="1" fill="white" />
          <rect x="3" y="6" width="12" height="3" rx="1" fill="white" />
        </svg>
        <div
          v-if="type === 'collection'"
          class="rounded font-semibold text-xs min-w-[18px] w-[14px] py-[1px] bg-timeline-collection-count flex items-center justify-center"
        >
          {{ (element as AdSetsType).ads.length }}
        </div>
        <span v-if="parseEnd - parseStart < 5">
          {{ element.name.slice(0, 2) }}...
        </span>
        <span v-else>
          {{ element.name }}
        </span>
      </div>
    </div>
  </div>
</template>
