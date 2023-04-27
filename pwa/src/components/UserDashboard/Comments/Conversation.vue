<script setup lang="ts">
import {
  handleAddComment,
  handleRemoveConversation,
  handleStartConversation
} from "@/services/api/comments";
import type { ConversationsType } from "@/stores/userDashboard";
import { computed, ref } from "vue";
import { useI18n } from "vue-i18n";

type PropsType = {
  modelValue: boolean;
  xPosition?: number;
  yPosition?: number;
  conversation?: ConversationsType;
};

const props = defineProps<PropsType>();
const { t } = useI18n();

const emits = defineEmits<{
  (e: "update:modelValue", value: boolean): void;
  (e: "updated"): void;
}>();

const textarea = ref(null);
const isModalWindowVisible = ref<boolean>(props.modelValue);
const isFocused = ref<boolean>(false);
const commentContent = ref<string>("");

const weekday = computed<string>(() =>
  props.conversation
    ? new Date(props.conversation.date).getDay().toString()
    : new Date().getDay().toString()
);

const dateFormat = (d?: string) => {
  let date = d ? new Date(d) : new Date();

  return `${date.getDate() < 10 ? `0${date.getDate()}` : date.getDate()}.${
    date.getMonth() + 1 < 10 ? `0${date.getMonth() + 1}` : date.getMonth() + 1
  }`;
};

const commentDate = (date: string) => {
  let today = new Date();
  let difference =
    (today.getTime() - new Date(date).getTime()) / (1000 * 60 * 60 * 24);
  let result = null;

  if (dateFormat(date) === dateFormat(today.toString())) {
    result = t("components.user-dashboard.conversation.today");
  } else if (difference >= 1 && difference < 2) {
    result = `1 ${t("components.user-dashboard.conversation.day-ago")}`;
  } else {
    result = `${Math.floor(difference)} ${t(
      "components.user-dashboard.conversation.days-ago"
    )}`;
  }

  return result;
};

const deleteConversation = async () => {
  await handleRemoveConversation(props.conversation.id);

  emits("update:modelValue", false);
  isModalWindowVisible.value = false;
  emits("updated");
};

const startConversation = async () => {
  if (commentContent.value.length) {
    await handleStartConversation(commentContent.value);

    commentContent.value = "";
    emits("updated");
  }
};

const addComment = async () => {
  if (commentContent.value.length) {
    await handleAddComment({
      conversationId: props.conversation.id,
      comment: commentContent.value
    });

    commentContent.value = "";
    emits("updated");
  }
};
</script>

<template>
  <ModalWindow
    v-model="isModalWindowVisible"
    :width="230"
    :x="xPosition"
    :y="yPosition"
    isConversation
  >
    <div class="grid grid-flow-row">
      <div class="flex justify-between items-center">
        <h4 class="text-base text-black font-bold">
          {{ t(`components.user-dashboard.conversation.weekdays.${weekday}`) }}
          {{ dateFormat(conversation ? conversation.date : null) }}
        </h4>
        <div class="flex items-center">
          <button
            class="p-1 mr-1 focus:bg-bottombar-hover/50 hover:bg-bottombar-hover/50 rounded-md"
            @click="deleteConversation"
          >
            <IconSvg name="trash" :class-name="`w-[12px] h-[16px]`" />
          </button>
          <button
            class="p-1 focus:bg-bottombar-hover/50 hover:bg-bottombar-hover/50 rounded-md"
            @click="
              $emit('update:modelValue', false);
              isModalWindowVisible = false;
            "
          >
            <IconSvg
              name="close"
              :class-name="`w-[12px] h-[12px]`"
              color="#111111"
            />
          </button>
        </div>
      </div>
      <div v-if="conversation?.comments.length">
        <div
          class="py-2 border-b border-b-divider-default last:border-0 text-[10px] leading-3"
          v-for="comment in conversation.comments"
        >
          <p class="font-bold mb-2">
            {{ commentDate(comment.createdAt) }}
          </p>
          <p class="break-all">{{ comment.comment }}</p>
        </div>
      </div>
      <div
        class="relative flex sticky bottom-0 py-3 bg-comments-white"
        :class="{ 'mt-4': !conversation?.comments.length }"
      >
        <textarea
          ref="textarea"
          class="w-full p-[11px] pr-[30px] bg-comments-textarea text-comments-text rounded-[10px] text-[10px] resize-none leading-[11px] overflow-hidden"
          :class="{
            'scroll-bar overflow-x-hidden overflow-y-auto': isFocused
          }"
          placeholder="Add new comment"
          :rows="isFocused ? '5' : '1'"
          v-model="commentContent"
          @focus="isFocused = true"
          @focusout="isFocused = false"
        />
        <button
          class="absolute bottom-[16px] right-[5px] rounded-full w-[25px] h-[25px] flex items-center content-center hover:bg-comments-red focus:bg-comments-red"
          :class="{
            'bg-comments-red': isFocused,
            'bg-comments-grey': !isFocused
          }"
          @mousedown="conversation ? addComment() : startConversation()"
        >
          <IconSvg
            name="arrowTop"
            :class-name="`w-[11px] h-[13px] flex m-auto`"
          />
        </button>
      </div>
    </div>
  </ModalWindow>
</template>

<style lang="scss" scoped>
.scroll-bar::-webkit-scrollbar-track {
  border-radius: 10px;
  margin: 10px;
}
</style>
