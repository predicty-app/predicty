<script setup lang="ts">
import { useGlobalStore } from "@/stores/global";
import { useConversationsStore } from "@/stores/conversations";

const globalStore = useGlobalStore();
const conversationStore = useConversationsStore();

/**
 * Function to get position created conversation line.
 * @param {MouseEvent} eventMouse
 */
function handleGetPositionCreatedConversationLine(eventMouse: MouseEvent) {
  if (conversationStore.isProcessCreateConversationActive) {
    return;
  }

  conversationStore.createdConversationSetting.mousePosition.x =
    eventMouse.clientX -
    globalStore.scrollCampaignList.getBoundingClientRect().width +
    globalStore.scrollParams.x;
}
</script>
<template>
  <div
    @mousemove="handleGetPositionCreatedConversationLine"
    v-if="conversationStore.isCreateConversationActive"
    class="absolute w-full h-[98.5%] z-[9999] left-0 top-0"
  >
    <ConversationCommentsCreateLine v-if="conversationStore.canCreateConversation"/>
  </div>
</template>
