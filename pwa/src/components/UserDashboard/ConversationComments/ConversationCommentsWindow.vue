<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";
import { until } from "@vueuse/core";
import { hCommentDate } from "@/helpers/utils";
import { useConversationsStore } from "@/stores/conversations";
import type { CommentType, ConversationsType } from "@/stores/userDashboard";
import { TypesWindowConversation } from "@/stores/conversations";
import {
  handleStartConversation,
  handleAddComment
} from "@/services/api/conversation";

type PropsType = {
  conversationElement?: ConversationsType;
  typeWindow: TypesWindowConversation;
  positionY?: number;
};

type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const { t } = useI18n();
const props = defineProps<PropsType>();
const commentMessage = ref<string>("");
const conversationStore = useConversationsStore();
const notificationMessageModel = ref<NotificationMessageType>({
  visible: false,
  type: "success",
  message: ""
});
const windowInstance = ref<HTMLDivElement | null>(null);
const borderColor = computed<string>(() =>
  props.typeWindow !== TypesWindowConversation.CREATE
    ? props.conversationElement.color.hex
    : conversationStore.createdConversationSetting.color
);
const windowPositionY = computed<string>(() =>
  props.typeWindow !== TypesWindowConversation.CREATE
    ? `${props.positionY}px`
    : `${conversationStore.createdConversationSetting.linePosition.y}px`
);
const backgroundButtonColor = computed<string>(() =>
  props.typeWindow !== TypesWindowConversation.CREATE
    ? props.conversationElement.color.hex
    : conversationStore.createdConversationSetting.color
);
const commentsList = computed<CommentType[]>(() => {
  let comments = props.conversationElement
    ? props.conversationElement.comments
    : [];

  comments.sort(
    (commentA: CommentType, commentB: CommentType) =>
      Date.parse(commentB.createdAt) - Date.parse(commentA.createdAt)
  );

  return comments;
});

const emit = defineEmits<{
  (e: "handleExitEditMode"): void;
  (e: "handleShowPromptRemoveConversation"): void;
  (e: "handleUpdated"): void;
}>();

/**
 * Function to get day by name.
 * @return {string}
 */
function getDayNameByDate(): string {
  const date =
    props.typeWindow === TypesWindowConversation.CREATE
      ? conversationStore.createdConversationSetting.date
      : props.conversationElement.date;
  const day = new Date(date).toLocaleString("en-us", { weekday: "short" });
  return `${t(
    `components.user-dashboard.conversation-comments.conversation-comments-window.days.${day}`
  )} ${date.slice(8, 10)}.${date.slice(5, 7)}`;
}

/**
 * Function to handle reload creating conversation.
 */
function handleReloadCreatingConversation() {
  commentMessage.value = "";
  conversationStore.isProcessCreateConversationActive = false;
  emit("handleExitEditMode");
}

/**
 * Function to handle create conversation or assign new comment.
 */
async function handleCreateConversationOrAssignComment() {
  if (!commentMessage.value) {
    return;
  }

  if (props.typeWindow === TypesWindowConversation.CREATE) {
    await handleStartConversation({
      comment: commentMessage.value,
      color: conversationStore.createdConversationSetting.color,
      date: conversationStore.createdConversationSetting.date
    });

    notificationMessageModel.value.type = "success";
    notificationMessageModel.value.visible = true;
    notificationMessageModel.value.message = t(
      "components.user-dashboard.conversation-comments.conversation-comments-window.notifications.create-conversation.success"
    );

    await until(ref).toBe(true, { timeout: 1800 });
  }

  if (props.typeWindow === TypesWindowConversation.DETAILS) {
    await handleAddComment({
      comment: commentMessage.value,
      conversationId: props.conversationElement.id
    });

    notificationMessageModel.value.type = "success";
    notificationMessageModel.value.visible = true;
    notificationMessageModel.value.message = t(
      "components.user-dashboard.conversation-comments.conversation-comments-window.notifications.create-comment.success"
    );

    await until(ref).toBe(true, { timeout: 1000 });
  }

  commentMessage.value = "";
  emit("handleUpdated");
  conversationStore.resetSettingsCreateConversationAction();
}
</script>
<template>
  <NotificationMessage
    v-model="notificationMessageModel.visible"
    :message="notificationMessageModel.message"
    :type="notificationMessageModel.type"
  />
  <div
    ref="windowInstance"
    :class="[
      'animate-fade-in w-[230px] rounded-xl border-dynamic p-3 border-2 absolute bg-basic-white drop-shadow-md flex flex-col gap-y-1',
      {
        'left-[50%] translate-x-[-50%]': TypesWindowConversation.CREATE,
        'translate-x-[2%]': [
          TypesWindowConversation.PREVIEW,
          TypesWindowConversation.DETAILS
        ].includes(typeWindow)
      }
    ]"
    :style="{ '--border': borderColor, top: windowPositionY }"
  >
    <div class="flex justify-between items-center w-full">
      <div
        :class="[
          'font-bold',
          {
            'text-sm': [
              TypesWindowConversation.CREATE,
              TypesWindowConversation.DETAILS
            ].includes(typeWindow),
            'text-[12px]': typeWindow === TypesWindowConversation.PREVIEW
          }
        ]"
      >
        {{ getDayNameByDate() }}
        <span
          class="font-normal"
          v-if="[TypesWindowConversation.PREVIEW].includes(typeWindow)"
          >{{ hCommentDate(commentsList.at(0).createdAt, t) }}</span
        >
      </div>
      <div class="flex gap-x-1">
        <IconSvg
          v-if="[TypesWindowConversation.DETAILS].includes(typeWindow)"
          name="trash"
          class-name="w-3 h-3 fill-gray-1300 cursor-pointer"
          @click="emit('handleShowPromptRemoveConversation')"
        />
        <IconSvg
          v-if="
            [
              TypesWindowConversation.CREATE,
              TypesWindowConversation.DETAILS
            ].includes(typeWindow)
          "
          name="close"
          class-name="w-3 h-3 fill-gray-1300 cursor-pointer"
          @click="handleReloadCreatingConversation"
        />
      </div>
    </div>
    <div
      :class="[
        {
          'text-xs': [TypesWindowConversation.PREVIEW].includes(typeWindow),
          'text-sm': [TypesWindowConversation.DETAILS].includes(typeWindow)
        }
      ]"
      v-if="[TypesWindowConversation.PREVIEW].includes(typeWindow)"
    >
      {{
        commentsList.at(0).comment.length > 30
          ? `${commentsList.at(0).comment.slice(0, 30)}...`
          : commentsList.at(0).comment
      }}
    </div>
    <div
      v-if="[TypesWindowConversation.DETAILS].includes(typeWindow)"
      class="mb-3"
    >
      <ScrollbarPanel
        class="max-h-[120px]"
        :is-vertical-scroll-visible="false"
        :is-horizontal-scroll-visible="true"
      >
        <div
          :key="comment.comment"
          v-for="(comment, index) in commentsList"
          class="flex flex-col gap-y-2 pr-4"
        >
          <div class="text-[10px] font-bold">
            {{ hCommentDate(comment.createdAt, t) }}
          </div>
          <p
            class="text-[12px] w-[190px] leading-4"
            style="white-space: break-spaces"
          >
            {{ comment.comment }}
          </p>
          <DividerLine v-if="index < commentsList.length - 1" />
        </div>
      </ScrollbarPanel>
    </div>
    <div
      class="relative"
      v-if="
        [
          TypesWindowConversation.CREATE,
          TypesWindowConversation.DETAILS
        ].includes(typeWindow)
      "
    >
      <textarea
        v-model="commentMessage"
        :placeholder="
          t(
            'components.user-dashboard.conversation-comments.conversation-comments-window.comment.placeholder'
          )
        "
        :class="[
          'text-[10px] resize-none w-full scroll-bar h-28 p-3 rounded-xl outline-none bg-gray-300/50 text-gray-900 transition-all overflow-hidden focus:h-28 focus:overflow-auto',
          {
            'h-28 overflow-auto': commentMessage,
            'h-10': !commentMessage
          }
        ]"
      />
      <button
        :class="[
          'rounded-full w-6 h-6 absolute bottom-3 right-1 flex items-center justify-center',
          {
            'bg-dynamic': commentMessage,
            'bg-gray-600': !commentMessage
          }
        ]"
        :style="{ '--background': backgroundButtonColor }"
        @click="handleCreateConversationOrAssignComment"
      >
        <IconSvg name="arrowTop" class-name="w-4 h-4" />
      </button>
    </div>
  </div>
</template>
