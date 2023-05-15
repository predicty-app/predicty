<script setup lang="ts">
import { useI18n } from "vue-i18n";
import { until } from "@vueuse/core";
import { ref, watch, onMounted } from "vue";
import { useGlobalStore } from "@/stores/global";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { useConversationsStore } from "@/stores/conversations";
import type { ConversationsType } from "@/stores/userDashboard";
import { TypesWindowConversation } from "@/stores/conversations";
import {
  handleRemoveConversation,
  handleGetConversations
} from "@/services/api/conversation";

type PropsType = {
  conversationDate: string;
};

type NotificationMessageType = {
  visible: boolean;
  type: "success" | "error";
  message: string;
};

const { t } = useI18n();
const globalStore = useGlobalStore();
const yLinePosition = ref<number>(0);
const props = defineProps<PropsType>();
const isHoverElement = ref<boolean>(false);
const isSpinnerVisible = ref<boolean>(false);
const isEditModeVisible = ref<boolean>(false);
const notificationMessageModel = ref<NotificationMessageType>({
  visible: false,
  type: "success",
  message: ""
});
const conversationStore = useConversationsStore();
const userDashboardStore = useUserDashboardStore();
const elementInstance = ref<HTMLDivElement | null>(null);
const conversationElement = ref<ConversationsType | null>(null);

onMounted(() => {
  checkIsLineVisible();
});

watch(
  () => conversationStore.createdConversationSetting.mousePosition.x,
  () => {
    if (conversationStore.isCreateConversationActive) {
      const leftPosition =
        elementInstance.value.getBoundingClientRect().left -
        globalStore.scrollCampaignList.getBoundingClientRect().width;
      const xPositionStart = leftPosition + globalStore.scrollParams.x;
      const xPositionEnd =
        leftPosition +
        elementInstance.value.getBoundingClientRect().width +
        globalStore.scrollParams.x;

      if (
        xPositionStart <
          conversationStore.createdConversationSetting.mousePosition.x &&
        conversationStore.createdConversationSetting.mousePosition.x <
          xPositionEnd
      ) {
        conversationStore.canCreateConversation = conversationElement.value
          ? false
          : true;

        conversationStore.createdConversationSetting.linePosition.x =
          leftPosition;
        conversationStore.createdConversationSetting.date =
          props.conversationDate;
      }
    }
  }
);

watch(
  () => userDashboardStore.conversations.length,
  () => {
    checkIsLineVisible();
  }
);

/**
 * Function to check is line visible.
 */
function checkIsLineVisible() {
  conversationElement.value = userDashboardStore.conversations.find(
    (conversation: ConversationsType) =>
      conversation.date === props.conversationDate
  );
}

/**
 * Function yo move button create conversation.
 * @param {MouseEvent} eventMouse
 */
function handleMoveButtonCreateConversation(eventMouse: MouseEvent) {
  if (
    conversationStore.isProcessCreateConversationActive ||
    isEditModeVisible.value
  ) {
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

/**
 * Function to handle exit mode.
 */
function handleExitEditMode() {
  isHoverElement.value = false;
  isEditModeVisible.value = false;
  checkIsLineVisible();
}

/**
 * Function to handle remove conversation.
 */
async function handleSubmitRemoveConversation() {
  isSpinnerVisible.value = true;

  await handleRemoveConversation(conversationElement.value.id);
  notificationMessageModel.value.type = "success";
  notificationMessageModel.value.visible = true;
  notificationMessageModel.value.message = t(
    "components.user-dashboard.conversation-comments.conversation-comments-line.notifications.remove-conversation.success"
  );

  await until(ref).toBe(true, { timeout: 1800 });
  await handleGetConversations();

  isSpinnerVisible.value = false;
  isEditModeVisible.value = false;
}
</script>
<template>
  <SpinnerBar :is-visible="isSpinnerVisible" :is-global="true" />
  <NotificationMessage
    v-model="notificationMessageModel.visible"
    :message="notificationMessageModel.message"
    :type="notificationMessageModel.type"
  />
  <div ref="elementInstance">
    <div
      @click="isEditModeVisible = true"
      v-if="conversationElement && conversationStore.isConversationsVisible"
      @mouseenter="handleToggleHoverElementState(true)"
      @mouseleave="handleToggleHoverElementState(false)"
      @mousemove="handleMoveButtonCreateConversation"
      :class="[
        'bg-dynamic h-[100%] relative w-[16px] top-0 animate-fade-in',
        {
          'z-[20]': !isHoverElement,
          'z-[100]': isHoverElement
        }
      ]"
    >
      <div
        class="z-[20] w-6 h-6 bg-dynamic rounded-full text-xs font-medium absolute left-[50%] translate-x-[-50%] top-3 flex items-center justify-center text-conversationCommentsCreateForm-text-color"
        :style="{ '--background': conversationElement.color.hex }"
      >
        {{ conversationElement.comments.length }}
      </div>
      <div
        class="h-full opacity-10 bg-dynamic w-[16px]"
        :style="{ '--background': conversationElement.color.hex }"
      />
      <div
        class="absolute left-[0.5px] right-0 top-0 h-full border-r-2 m-auto border-dashed border-dynamic w-[1px]"
        :style="{ '--border': conversationElement.color.hex }"
      />

      <ConversationCommentsWindow
        v-if="isHoverElement"
        :position-y="yLinePosition"
        :conversation-element="conversationElement"
        @handleExitEditMode="handleExitEditMode()"
        @handleShowPromptRemoveConversation="handleSubmitRemoveConversation"
        :type-window="
          TypesWindowConversation[isEditModeVisible ? 'DETAILS' : 'PREVIEW']
        "
      />
    </div>
  </div>
  <Teleport to="body" v-if="isEditModeVisible"> </Teleport>
</template>
