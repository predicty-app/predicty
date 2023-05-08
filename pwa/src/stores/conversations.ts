import { defineStore } from "pinia";

type ConversationPositionType = {
  x: number;
  y: number;
};

type CreatedConversationSettingType = {
  date: string | null;
  color: string;
  position: ConversationPositionType;
  comment: string | null;
};

type StateType = {
  createdConversationSetting: CreatedConversationSettingType;
  isConversationsVisible: boolean;
  isCreateConversationActive: boolean;
};

export const useConversationsStore = defineStore({
  id: "conversations",
  state: () =>
    ({
      createdConversationSetting: {
        date: null,
        color: "#E24963",
        position: {
          x: 0,
          y: 0
        },
        comment: null
      },
      isCreateConversationActive: false,
      isConversationsVisible: true
    } as StateType)
});
