import apiService from "@/services/api/api";
import { useUserDashboardStore } from "@/stores/userDashboard";

type CommentPayloadType = {
  conversationId: string;
  comment: string;
};

type StartConversationPayloadType = {
  color: string;
  comment: string;
  date: string;
}

/**
 * Function to handle conversations list.
 */
async function handleGetConversations() {
  const query = `query Dashboard {
    dashboard {
      conversations {
        id
        date
        color {
          hex
        }
        comments {
          createdAt
          comment
        }
      }
    }
  }`;

  try {
    const response = await apiService.request<any, any>(query);

    if(!response.errors) {
      const userDashboard = useUserDashboardStore();
      userDashboard.conversations = response.data.dashboard.conversations;
    }

    return response.errors ? null : 'OK';
  } catch (error) {
    return null;
  }
}

/**
 * Function to start a conversation.
 * @param {StartConversationPayloadType} payload
 */
async function handleStartConversation(payload: StartConversationPayloadType) {
  type ConversationType = {
    color: string;
    comment: string;
    date: string;
  };

  const query = `mutation startConversation($color: String, $comment: String, $date: Date!) {
      startConversation(color: $color, comment: $comment, date: $date)
    }`;

  try {
    const response = await apiService.request<ConversationType, any>(query, { ...payload });

    return response.errors ? null : 'OK';
  } catch (error) {
    return null;
  }
}

/**
 * Function to remove a conversation.
 */
async function handleRemoveConversation(conversationId: string) {
  type ConversationIdype = {
    conversationId: string;
  };

  const query = `mutation removeConversation($conversationId: ID!) {
      removeConversation(conversationId: $conversationId)
    }`;

  try {
    const response = await apiService.request<ConversationIdype, any>(query, {
      conversationId: conversationId
    });

    return response.errors ? null : response.data.imports;
  } catch (error) {
    return null;
  }
}

/**
 * Function to add a comment to a conversation.
 */
async function handleAddComment(payload: CommentPayloadType) {
  const query = `mutation addConversationComment($conversationId: ID!, $comment: String!) {
      addConversationComment(conversationId: $conversationId, comment: $comment)
    }`;

  try {
    const response = await apiService.request<CommentPayloadType, any>(query, {
      conversationId: payload.conversationId,
      comment: payload.comment
    });

    return response.errors ? null : 'OK';
  } catch (error) {
    return null;
  }
}

export {
  handleGetConversations,
  handleStartConversation,
  handleRemoveConversation,
  handleAddComment
};
