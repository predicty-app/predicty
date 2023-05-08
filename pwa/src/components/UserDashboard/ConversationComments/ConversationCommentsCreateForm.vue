<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";
import { ColorPicker } from "vue-color-kit";
import "vue-color-kit/dist/vue-color-kit.css";
import { useConversationsStore } from "@/stores/conversations";

type HSVType = {
  h: number;
  s: number;
  v: number;
};

type RGBAType = {
  r: number;
  g: number;
  b: number;
  a: number;
};

type ColorPickerType = {
  hex: string;
  hsv: HSVType;
  rgba: RGBAType;
};

const { t } = useI18n();
const conversationStore = useConversationsStore();
const isColorPickerVisible = ref<boolean>(false);
const iconNameVisibilityConversations = computed<string>(() =>
  conversationStore.isConversationsVisible ? "hidden" : "visible"
);

/**
 * Function to change state of creating new conversation.
 * @param {boolean} state
 */
function toggleStateCreateConversation(state: boolean) {
  conversationStore.isCreateConversationActive = state;
  isColorPickerVisible.value = false;
  if (state) {
    conversationStore.isConversationsVisible = true;
  }
}

function handleChangeColorConversation(color: ColorPickerType) {
  conversationStore.createdConversationSetting.color = color.hex;
  isColorPickerVisible.value = false;
}
</script>

<template>
  <div
    class="animate-fade-in-up fixed bottom-2 left-2 bg-conversationCommentsCreateForm-background p-3 z-50 rounded-lg"
  >
    <div
      v-if="!conversationStore.isCreateConversationActive"
      class="animate-fade-in flex gap-x-1 items-center"
    >
      <IconSvg
        name="conversation"
        class-name="animate-fade-in w-6 h-6 fill-conversationCommentsCreateForm-icons-fill cursor-pointer"
        @click="toggleStateCreateConversation(true)"
      />
      <div class="w-7 h-6 animate-fade-in pl-1">
        <IconSvg
          :name="iconNameVisibilityConversations"
          class-name="w-6 h-6 fill-conversationCommentsCreateForm-icons-fill cursor-pointer"
          @click="
            conversationStore.isConversationsVisible =
              !conversationStore.isConversationsVisible
          "
        />
      </div>
    </div>
    <div
      v-if="conversationStore.isCreateConversationActive"
      class="animate-fade-in flex gap-x-2 items-center relative"
    >
      <ColorPicker
        v-if="isColorPickerVisible"
        class="absolute bottom-8"
        theme="light"
        :color="conversationStore.createdConversationSetting.color"
        :sucker-hide="false"
        :sucker-canvas="null"
        :sucker-area="[]"
        @changeColor="handleChangeColorConversation"
      />

      <div
        class="w-6 h-6 bg-dynamic rounded-full cursor-pointer"
        :style="{
          '--background': conversationStore.createdConversationSetting.color
        }"
        @click="isColorPickerVisible = !isColorPickerVisible"
      />
      <span
        class="cursor-pointer animate-fade-in text-sm font-bold text-conversationCommentsCreateForm-text-color"
        @click="toggleStateCreateConversation(false)"
      >
        {{
          t(
            "components.user-dashboard.conversation-comments.conversation-comments-create-form.cancel"
          )
        }}
      </span>
    </div>
  </div>
</template>