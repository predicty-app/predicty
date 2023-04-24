import apiService from "@/services/api/api";
import { useUserDashboardStore } from "@/stores/userDashboard";

/**
 * Function to handle user logged params.
 */
async function handleAuthenticatedUser() {
  const query = `query Me {
    me {
      uid
      email
      isEmailVerified
      isOnboardingComplete
    }
  }`;

  const userDashboardStore = useUserDashboardStore();

  type AuthenticatedUserType = {
    uid: string;
    email: string;
    isEmailVerified: boolean;
  };

  try {
    const response = await apiService.request<AuthenticatedUserType, any>(
      query
    );
    if (!response.errors) {
      userDashboardStore.setAuthenticatedUserParams(response.data.me);
    }

    return response.errors ? null : response.data;
  } catch (error) {
    return null;
  }
}

/**
 * Function to handle logout user.
 */
async function handleDeleteAuthenticationUser() {
  const query = `mutation Logout {
    logout
  }`;

  type DeleteAuthenticationUserType = {
    data: {
      logout: string;
    };
  };

  try {
    const response = await apiService.request<
      DeleteAuthenticationUserType,
      any
    >(query);
    return response.errors ? null : response.data;
  } catch (error) {
    return null;
  }
}

export { handleAuthenticatedUser, handleDeleteAuthenticationUser };
