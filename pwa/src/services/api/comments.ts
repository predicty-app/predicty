import apiService from "@/services/api/api";

/**
 * Function to handle conversations list.
 */
async function handleGetConversations() {
  const query = `query Dashboard {
    dashboard {
      conversations {
        id
        user {
          uid
          email
        }
        date
        color {
          hex
        }
        comments {
          createdAt
          user {
            uid
            email
          }
          comment
          isEditable
        }
        isRemovable
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
async function handleStartConversation() {
  const query = ``;

  try {
    const response = await apiService.request<any, any>(query);

    return response.errors ? null : response.data.imports;
  } catch (error) {
    return null;
  }
}

/**
 * Function to remove a conversation.
 */
async function handleRemoveConversation() {
  const query = ``;

  try {
    const response = await apiService.request<any, any>(query);

    return response.errors ? null : response.data.imports;
  } catch (error) {
    return null;
  }
}

/**
 * Function to add a comment to a conversation.
 */
async function handleAddComment() {
  const query = ``;

  try {
    const response = await apiService.request<any, any>(query);

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
