<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";
import { ColorPicker } from "vue-color-kit";
import "vue-color-kit/dist/vue-color-kit.css";
import { useConversationsStore } from "@/stores/conversations";
import { ActionHotkeyType } from "@/stores/conversations";
import { useUserDashboardStore } from "@/stores/userDashboard";

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
const isColorPickerVisible = ref<boolean>(false);
const conversationStore = useConversationsStore();
const iconNameVisibilityConversations = computed<string>(() =>
  conversationStore.isConversationsVisible ? "hidden" : "visible"
);
const userDashboard = useUserDashboardStore();

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

/**
 * Function to handle get change color conversation.
 * @param {ColorPickerType} color
 */
function handleChangeColorConversation(color: ColorPickerType) {
  conversationStore.createdConversationSetting.color = color.hex;
  isColorPickerVisible.value = false;
}

/**
 * Function to handle hotkey action.
 * @param {ActionHotkeyType} action
 */
function handleHotkeyAction(action: ActionHotkeyType) {
  if (userDashboard.selectedCollection) {
    return;
  }
  const state = action === ActionHotkeyType.ACTIVE_CONVERSATION;
  toggleStateCreateConversation(state);
}
</script>

<template>
  <HotKeys @handleAction="handleHotkeyAction" />
  <div
    class="animate-fade-in-up fixed bottom-2 left-2 bg-gray-1100 p-3 z-50 rounded-lg"
  >
    <div
      v-if="!conversationStore.isCreateConversationActive"
      class="animate-fade-in flex gap-x-1 items-center"
    >
      <IconSvg
        name="conversation"
        class-name="animate-fade-in w-6 h-6 fill-basic-white cursor-pointer"
        @click="toggleStateCreateConversation(true)"
      />
      <div class="w-7 h-6 animate-fade-in pl-1">
        <IconSvg
          :name="iconNameVisibilityConversations"
          class-name="w-6 h-6 fill-basic-white cursor-pointer"
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
        class="cursor-pointer animate-fade-in text-sm font-bold text-basic-white"
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
