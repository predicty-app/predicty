<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { ref, watch, onMounted, computed } from 'vue';
import { useUserDashboardStore } from '@/stores/userDashboard';
import { useConversationsStore } from "@/stores/conversations";
import type { ConversationsType } from '@/stores/userDashboard';
import { TypesWindowConversation } from '@/stores/conversations';

type PropsType = {
  conversationDate: string;
}
const globalStore = useGlobalStore();
const yLinePosition = ref<number>(0);
const props = defineProps<PropsType>();
const isHoverElement = ref<boolean>(false);
const isEditModeVisible = ref<boolean>(false);
const conversationStore = useConversationsStore();
const userDashboardStore = useUserDashboardStore();
const elementInstance = ref<HTMLDivElement | null>(null);
const conversationElement = ref<ConversationsType | null>(null);

onMounted(() => {
  checkIsLineVisible();
})

watch(() => conversationStore.createdConversationSetting.mousePosition.x, () => {
  if (conversationStore.isCreateConversationActive) {
    const leftPosition = elementInstance.value.getBoundingClientRect().left - globalStore.scrollCampaignList.getBoundingClientRect().width;
    const xPositionStart = leftPosition + globalStore.scrollParams.x;
    const xPositionEnd = leftPosition + elementInstance.value.getBoundingClientRect().width + globalStore.scrollParams.x;

    if (xPositionStart < conversationStore.createdConversationSetting.mousePosition.x && conversationStore.createdConversationSetting.mousePosition.x < xPositionEnd) {
      conversationStore.createdConversationSetting.linePosition.x = leftPosition;
      conversationStore.createdConversationSetting.date = props.conversationDate;
    }
  }
});

watch(() => userDashboardStore.conversations.length, () => {
  checkIsLineVisible();
});

/**
 * Function to check is line visible.
 */
function checkIsLineVisible() {
  conversationElement.value = userDashboardStore.conversations.find((conversation: ConversationsType) => conversation.date === props.conversationDate);
}

/**
 * Function yo move button create conversation.
 * @param {MouseEvent} eventMouse 
 */
function handleMoveButtonCreateConversation(eventMouse: MouseEvent) {
  if (conversationStore.isProcessCreateConversationActive || isEditModeVisible.value) {
    return false;
  }

  yLinePosition.value = eventMouse.clientY - 80;
  if (yLinePosition.value < 0) {
    yLinePosition.value = 0;
  }
}

/**
 * Function to toggle state hover element.
 * @param {boolean} state 
 */
function handleToggleHoverElementState(state: boolean) {
  isHoverElement.value = state;
  isEditModeVisible.value = false;
}

function handleExitEditMode() {
  isHoverElement.value = false;
  isEditModeVisible.value = false;
}

</script>
<template>
  <div ref="elementInstance">
    <div @click="isEditModeVisible = true" v-if="conversationElement && conversationStore.isConversationsVisible"
      @mouseenter="handleToggleHoverElementState(true)" @mouseleave="handleToggleHoverElementState(false)"
      @mousemove="handleMoveButtonCreateConversation" :class="['bg-dynamic h-[100%] relative w-[16px] top-0', {
        'z-[20]': !isHoverElement,
        'z-[100]': isHoverElement
      }]">
      <div class="h-full opacity-10 bg-dynamic w-[16px]" :style="{ '--background': conversationElement.color.hex }" />
      <div class="absolute left-[0.5px] right-0 top-0 h-full border-r-2 m-auto border-dashed border-dynamic w-[1px]"
        :style="{ '--border': conversationElement.color.hex }" />

      <ConversationCommentsWindow v-if="isHoverElement" :position-y="yLinePosition"
        :conversation-element="conversationElement"
        @handleExitEditMode="handleExitEditMode()"
        :type-window="TypesWindowConversation[isEditModeVisible ? 'DETAILS' : 'PREVIEW']" />
    </div>
</div></template>