<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { computed, ref } from 'vue';
import { hCommentDate } from '@/helpers/utils';
import { useConversationsStore } from "@/stores/conversations";
import type { ConversationsType } from '@/stores/userDashboard';
import { TypesWindowConversation } from '@/stores/conversations';
import { handleStartConversation, handleGetConversations, handleAddComment } from '@/services/api/conversation';

type PropsType = {
  conversationElement?: ConversationsType;
  typeWindow: TypesWindowConversation
  positionY?: number
}

const { t } = useI18n();
const props = defineProps<PropsType>();
const commentMessage = ref<string>('');
const isSpinnerVisible = ref<boolean>(false);
const conversationStore = useConversationsStore();
const borderColor = computed<string>(() => props.typeWindow !== TypesWindowConversation.CREATE ? props.conversationElement.color.hex : conversationStore.createdConversationSetting.color);
const windowPositionY = computed<string>(() => props.typeWindow !== TypesWindowConversation.CREATE ? `${props.positionY}px` : `${conversationStore.createdConversationSetting.linePosition.y}px`);
const backgroundButtonColor = computed<string>(() => props.typeWindow !== TypesWindowConversation.CREATE ? props.conversationElement.color.hex : conversationStore.createdConversationSetting.color);


const emit = defineEmits<{
  (e: "handleExitEditMode"): void;
}>();

/**
 * Function to get day by name.
 * @return {string}
 */
function getDayNameByDate(): string {
  const date = props.typeWindow === TypesWindowConversation.CREATE ? conversationStore.createdConversationSetting.date : props.conversationElement.date;
  const day = new Date(date).toLocaleString('en-us', { weekday: 'short' });
  return `${t(`components.user-dashboard.conversation-comments.conversation-comments-window.days.${day}`)} ${date.slice(8, 10)}.${date.slice(5, 7)}`;
}

/**
 * Function to handle reload creating conversation.
 */
function handleReloadCreatingConversation() {
  commentMessage.value = '';
  conversationStore.isProcessCreateConversationActive = false;
  emit('handleExitEditMode');
}

/**
 * Function to handle create conversation or assign new comment.
 */
async function handleCreateConversationOrAssignComment() {
  if (!commentMessage.value) {
    return;
  }
  isSpinnerVisible.value = true;

  if (props.typeWindow === TypesWindowConversation.CREATE) {
    await handleStartConversation({
      comment: commentMessage.value,
      color: conversationStore.createdConversationSetting.color,
      date: conversationStore.createdConversationSetting.date
    });
  }

  if (props.typeWindow === TypesWindowConversation.DETAILS) {
    await handleAddComment({
      comment: commentMessage.value,
      conversationId: props.conversationElement.id
    });
  }

  await handleGetConversations();
  commentMessage.value = '';
  conversationStore.resetSettingsCreateConversationAction();
  isSpinnerVisible.value = false;
}
</script>
<template>
  <SpinnerBar :is-visible="isSpinnerVisible" :is-global="true" />
  <div :class="['animate-fade-in w-[230px] rounded-xl border-dynamic p-3 border-2 absolute bg-conversationCommentsWindow-background drop-shadow-md flex flex-col gap-y-1', {
    'left-[50%] translate-x-[-50%]': TypesWindowConversation.CREATE,
    'translate-x-[2%]': [TypesWindowConversation.PREVIEW, TypesWindowConversation.DETAILS].includes(typeWindow)
  }]" :style="{ '--border': borderColor, 'top': windowPositionY }">
    <div class="flex justify-between items-center w-full">
      <div :class="['font-bold', {
        'text-base': [TypesWindowConversation.CREATE, TypesWindowConversation.DETAILS].includes(typeWindow),
        'text-[10px]': typeWindow === TypesWindowConversation.PREVIEW
      }]">{{ getDayNameByDate() }}
        <span class="font-normal" v-if="[TypesWindowConversation.PREVIEW].includes(typeWindow)">{{
          hCommentDate(props.conversationElement.comments.at(-1).createdAt, t) }}</span>
      </div>
      <div>
        <IconSvg v-if="[TypesWindowConversation.CREATE, TypesWindowConversation.DETAILS].includes(typeWindow)"
          name="close" class-name="w-3 h-3 fill-conversationCommentsWindow-icons-fill cursor-pointer"
          @click="handleReloadCreatingConversation" />
      </div>
    </div>
    <div class="text-[10px]" v-if="[TypesWindowConversation.PREVIEW].includes(typeWindow)">
      {{ props.conversationElement.comments.at(-1).comment.length > 40 ?
        `${props.conversationElement.comments.at(-1).comment.slice(0, 40)}...` :
        props.conversationElement.comments.at(-1).comment }}
    </div>
    <div v-if="[TypesWindowConversation.DETAILS].includes(typeWindow)" class=" mb-3">
      <ScrollbarPanel class="max-h-[120px]" :is-vertical-scroll-visible="false" :is-horizontal-scroll-visible="true">
        <div :key="comment.comment" v-for="(comment, index) in props.conversationElement.comments"
          class="flex flex-col gap-y-2">
          <div class="text-[10px] font-bold">{{ hCommentDate(comment.createdAt, t) }}</div>
          <p class="text-[10px] w-[190px] leading-4" style="white-space: break-spaces;">{{ comment.comment }}</p>
          <DividerLine v-if="index < props.conversationElement.comments.length - 1" />
        </div>
      </ScrollbarPanel>
    </div>
    <div class="relative" v-if="[TypesWindowConversation.CREATE, TypesWindowConversation.DETAILS].includes(typeWindow)">
      <textarea v-model="commentMessage"
        :placeholder="t('components.user-dashboard.conversation-comments.conversation-comments-window.comment.placeholder')"
        class="text-[10px] resize-none w-full h-28 p-3 rounded-xl outline-none bg-conversationCommentsWindow-textarea-background text-conversationCommentsWindow-textarea-text" />
      <button :class="['rounded-full w-6 h-6 absolute bottom-3 right-1 flex items-center justify-center', {
        'bg-dynamic': commentMessage,
        'bg-conversationCommentsWindow-button-background-disabled': !commentMessage
      }]" :style="{'--background': backgroundButtonColor }" @click="handleCreateConversationOrAssignComment">
      <IconSvg name="arrowTop" class-name="w-4 h-4" />
    </button>
  </div>
</div></template>