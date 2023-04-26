<script setup lang="ts">
import { ref } from "vue";

type PropsType = {
  modelValue: boolean;
  xPosition?: number;
  yPosition?: number;
};

defineProps<PropsType>();

const textarea = ref(null);
const isModalWindowVisible = ref<boolean>(true);
const isFocused = ref<boolean>(false);
const commentContent = ref<string>("");

const deleteConversation = () => {
  console.log("delete");
};

const addComment = () => {
  console.log(commentContent.value);
  commentContent.value = "";
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
        <h4 class="text-base text-black font-bold">Monday 21.11</h4>
        <div class="flex items-center">
          <button
            class="p-1 mr-1 focus:bg-bottombar-hover/50 hover:bg-bottombar-hover/50 rounded-md"
            @click="deleteConversation"
          >
            <IconSvg name="trash" :class-name="`w-[12px] h-[16px]`" />
          </button>
          <button
            class="p-1 focus:bg-bottombar-hover/50 hover:bg-bottombar-hover/50 rounded-md"
            @click="isModalWindowVisible = false"
          >
            <IconSvg
              name="close"
              :class-name="`w-[12px] h-[12px]`"
              color="#111111"
            />
          </button>
        </div>
      </div>
      <div>
        <div
          class="py-2 border-b border-b-divider-default last:border-0 text-[10px] leading-3"
        >
          <p class="font-bold mb-2">2 days ago</p>
          <p>
            We launched new website here, analytics may be skewed so please
            double check everything that happens next 14 days.
          </p>
        </div>
        <div
          class="py-2 border-b border-b-divider-default last:border-0 text-[10px] leading-3"
        >
          <p class="font-bold mb-2">6 days ago</p>
          <p>
            New website release is planned for this date, please don’t plan
            anything critical for this sprint.
          </p>
        </div>
      </div>
      <div class="pt-1 relative flex">
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
          class="absolute bottom-[5px] right-[5px] rounded-full w-[25px] h-[25px] flex items-center content-center hover:bg-comments-red focus:bg-comments-red"
          :class="{
            'bg-comments-red': isFocused,
            'bg-comments-grey': !isFocused
          }"
          @mousedown="addComment"
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
