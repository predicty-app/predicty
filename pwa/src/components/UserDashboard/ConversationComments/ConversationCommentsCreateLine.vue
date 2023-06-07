<script setup lang="ts">
import { ref } from "vue";
import { useConversationsStore } from "@/stores/conversations";
import { TypesWindowConversation } from "@/stores/conversations";

const yLinePosition = ref<number>(0);
const conversationStore = useConversationsStore();

/**
 * Function yo move button create conversation.
 * @param {MouseEvent} eventMouse
 */
function handleMoveButtonCreateConversation(eventMouse: MouseEvent) {
  if (conversationStore.isProcessCreateConversationActive) {
    return false;
  }

  yLinePosition.value = eventMouse.clientY - 70;
  if (yLinePosition.value < 0) {
    yLinePosition.value = 0;
  }
}

function handleStartCreatingConversation() {
  conversationStore.isProcessCreateConversationActive = true;
  conversationStore.createdConversationSetting.linePosition.y =
    yLinePosition.value;
}
</script>
<template>
  <div
    v-if="conversationStore.isConversationsVisible"
    @mousemove="handleMoveButtonCreateConversation"
    class="h-[100%] relative w-[16px] top-0"
    :style="{
      left: `${conversationStore.createdConversationSetting.linePosition.x}px`
    }"
  >
    <div
      class="h-full opacity-10 bg-dynamic w-[16px]"
      :style="{
        '--background': conversationStore.createdConversationSetting.color
      }"
    />
    <div
      class="absolute left-[0.5px] right-0 top-0 h-full border-r-2 m-auto border-solid border-dynamic w-[1px]"
      :style="{
        '--border': conversationStore.createdConversationSetting.color
      }"
    />
    <div
      class="w-8 h-8 bg-dynamic absolute left-[50%] translate-x-[-50%] top-0 flex items-center justify-center rounded-full"
      :style="{
        '--background': conversationStore.createdConversationSetting.color,
        top: `${yLinePosition}px`
      }"
      @click="handleStartCreatingConversation"
      v-if="!conversationStore.isProcessCreateConversationActive"
    >
      <IconSvg name="plus" class-name="w-4 h-4 fill-basic-white" />
    </div>
    <ConversationCommentsWindow
      :type-window="TypesWindowConversation.CREATE"
      :position-y="yLinePosition"
      v-if="
        conversationStore.isProcessCreateConversationActive &&
        conversationStore.isCreateConversationActive
      "
    />
  </div>
</template>
