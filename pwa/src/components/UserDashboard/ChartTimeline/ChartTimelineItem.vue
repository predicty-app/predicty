<script setup lang="ts">
import { ref, onMounted, watch, computed } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { AdsType, AdsCollection } from "@/stores/userDashboard";
import { hLightenDarkenColor } from "@/helpers/utils";

type PropsType = {
  start: number;
  end: number;
  type?: "ad" | "collection";
  color?: string;
  isVisible?: boolean;
  campaingUid?: string;
  element: AdsType | AdsCollection;
  noName?: boolean;
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
const virtualizationParentBoxElement = ref<DOMRect | null>(null);
const timelineItemInstance = ref<HTMLDivElement | null>(null);
const isElementAssignCheckedCollection = computed<boolean>(() => {
  return (
    userStore.selectedAdsList.campaignUid !== props.campaingUid &&
    userStore.selectedAdsList.ads.length > 0
  );
});

const currentColor = computed<string>(() =>
  hLightenDarkenColor(
    props.color,
    userStore.selectedAdsList.ads.includes(props.element.uid) ? -30 : 0
  )
);

onMounted(() => {
  const parent = handleParentBySelector(
    timelineItemInstance.value,
    "data-virtualization"
  );
  virtualizationParentBoxElement.value = parent.getBoundingClientRect();
  boundingBoxElement.value = timelineItemInstance.value.getBoundingClientRect();

  handleVisibleElement();
});

watch(
  () => globalStore.scrollParams,
  () => {
    handleVisibleElement();
  }
);

watch(
  () => globalStore.currentScale,
  () => {
    handleVisibleElement();
  }
);

/**
 * Function to handle change state of element (checked/unchecked)
 */
function handleChangeState() {
  userStore.toogleAssignAdsAction(props.campaingUid, props.element.uid);
}

/**
 * Function to handle visible element.
 */
function handleVisibleElement() {
  const modifierValue = 300;

  const currentLeftPosition =
    boundingBoxElement.value.left - virtualizationParentBoxElement.value.left;
  const widthElement = boundingBoxElement.value.width + modifierValue;

  isElementVisible.value =
    (currentLeftPosition + widthElement) * (globalStore.currentScale * 0.01) >
      globalStore.scrollParams.x &&
    virtualizationParentBoxElement.value.width + globalStore.scrollParams.x >
      currentLeftPosition * (globalStore.currentScale * 0.01);

  // if(!props.isVisible) {
  //   isElementVisible.value = false
  // }
  // TODO VIRTUALIZATION TOP
  // && ((currentTopPosition + heightElement) > globalStore.scrollParams.y
  // && virtualizationParentBoxElement.value.height + globalStore.scrollParams.y  > (currentTopPosition * (globalStore.currentScale * 0.01)))
}

/**
 * Function to habdle parent by selector.
 * @param {HTMLElement} element
 * @param {string} selector
 * @reutnr {HTMLElement | null}
 */
function handleParentBySelector(
  element: HTMLElement,
  selector: string
): HTMLElement | null {
  if (!element.parentElement) {
    return;
  }

  if (element.parentElement.getAttribute(selector) === "true") {
    return element.parentElement;
  } else {
    return handleParentBySelector(element.parentElement, selector);
  }
}

defineEmits<{
  (e: "collectionSelected", value: AdsType | AdsCollection): void;
}>();
</script>

<template>
  <div
    v-if="isElementVisible"
    ref="timelineItemInstance"
    :class="[
      `col-start-dynamic col-end-dynamic p-[1.5px] rounded-[6px] h-fit`,
      {
        'border-[2px] border-timeline-item-border cursor-pointer':
          type === 'collection',
        'opacity-50': isElementAssignCheckedCollection,
      },
    ]"
    v-on="
      type === 'collection'
        ? { click: () => $emit('collectionSelected', props.element) }
        : {}
    "
    :style="{ '--start': start, '--end': end, '--color': currentColor }"
  >
    <div
      :class="[
        'p-2 text-xs rounded-[5px] shadow-sm flex gap-x-1 items-center text-text-white font-semibold bg-timeline-item-background',
        {
          'cursor-pointer': type === 'collection',
          ' shadow-lg shadow-timeline-shadow':
            userStore.selectedAdsList.ads.includes(element.uid),
        },
      ]"
      :style="{ '--color': currentColor }"
    >
      <CheckboxForm
        v-if="
          type === 'ad' &&
          !(
            userStore.selectedAdsList.campaignUid !== campaingUid &&
            userStore.selectedAdsList.ads.length > 0
          )
        "
        :color="currentColor"
        :is-checked="userStore.selectedAdsList.ads.includes(element.uid)"
        @on-change="handleChangeState"
      />
      <IconSvg v-if="type === 'collection'" name="bars" class-name="w-4 h-4" />
      <div
        v-if="type === 'collection'"
        class="rounded font-semibold text-xs w-[14px] py-[1px] bg-timeline-collection-count flex items-center justify-center"
      >
        {{ (element as AdsCollection).ads.length }}
      </div>
      <p v-if="!noName">{{ element.name }}</p>
    </div>
  </div>
</template>
