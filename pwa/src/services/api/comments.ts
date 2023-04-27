import apiService from "@/services/api/api";

type CommentPayloadType = {
  conversationId: string;
  comment: string;
};

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

    return response.errors ? null : response.data.imports;
  } catch (error) {
    return null;
  }
}

/**
 * Function to start a conversation.
 */
async function handleStartConversation(comment: string) {
  type ConversationType = {
    color: string;
    comment: string;
    date: string;
  };

  const query = `mutation startConversation($color: String, $comment: String, $date: Date!) {
      startConversation(color: $color, comment: $comment, date: $date)
    }`;

  try {
    const response = await apiService.request<ConversationType, any>(query, {
      color: "#E24963",
      comment: comment,
      date: new Date().toISOString().split("T")[0]
    });

    return response.errors ? null : response.data.imports;
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

    return response.errors ? null : response.data.imports;
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
