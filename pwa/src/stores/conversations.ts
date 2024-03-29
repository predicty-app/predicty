import { defineStore } from "pinia";

export enum TypesWindowConversation {
  CREATE = "create",
  PREVIEW = "preview",
  DETAILS = "details"
}

export enum ActionHotkeyType {
  ACTIVE_CONVERSATION = "active_conversation",
  DEACTIVATION_CONVERSATION = "deactivation_conversation"
}

type ConversationPositionType = {
  x: number;
  y?: number;
};

type CreatedConversationSettingType = {
  date: string | null;
  color: string;
  mousePosition: ConversationPositionType;
  linePosition: ConversationPositionType;
  comment: string | null;
};

type StateType = {
  canCreateConversation: boolean;
  isConversationsVisible: boolean;
  isCreateConversationActive: boolean;
  isProcessCreateConversationActive: boolean;
  createdConversationSetting: CreatedConversationSettingType;
};

export const useConversationsStore = defineStore({
  id: "conversations",
  state: () =>
    ({
      createdConversationSetting: {
        date: null,
        color: "#E24963",
        mousePosition: {
          x: 0
        },
        linePosition: {
          x: 0,
          y: 0
        },
        comment: null
      },
      isProcessCreateConversationActive: false,
      isCreateConversationActive: false,
      isConversationsVisible: true,
      canCreateConversation: true
    } as StateType),
  actions: {
    /**
     * Function to reset settings create conversation action.
     */
    resetSettingsCreateConversationAction() {
      this.isProcessCreateConversationActive = false;
      this.isCreateConversationActive = false;
      this.isConversationsVisible = true;

      this.createdConversationSetting.color = "#E24963";
      this.createdConversationSetting.comment = "";
      this.createdConversationSetting.linePosition = {
        x: 0,
        y: 0
      };
      this.createdConversationSetting.mousePosition = {
        x: 0
      };
    }
  }
});
