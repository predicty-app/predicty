import apiService from "@/services/api/api";

export type RegisterUserPayloadType = {
  email: string;
};

export type ResetPasswordPayloadType = {
  email: string;
};

export type LoginUserPayloadType = {
  username: string;
  passcode: string;
};

export type AuthLoginUserPayloadType = {
  username: string;
  password: string;
};

export type UploadFilePayloadType = {
  type: string;
  file: File;
  campaignName: string;
};

export type ConfirmResetPasswordPayloadType = {
  token: string;
  password: string;
};

/**
 * Function to handle register user.
 * @param {RegisterUserPayloadType} payload
 */
async function handleRegisterUser(payload: RegisterUserPayloadType) {
  type RegisterParamsType = {
    email: string;
  };

  const query = `mutation register($email: String!) {
    register(email: $email)
  }`;

  try {
    const response = await apiService.request<RegisterParamsType, any>(query, {
      email: payload.email
    });

    return response.errors
      ? response.errors[0].message
      : response.data.register;
  } catch (error) {
    return (error as Error).message;
  }
}

/**
 * Function to handle reset password.
 * @param {ResetPasswordPayloadType} payload
 */
async function handleResetPassword(payload: ResetPasswordPayloadType) {
  type ResetPasswordParamsType = {
    username: string;
  };

  const query = `mutation requestPasswordResetToken($username: String!) {
    requestPasswordResetToken(username: $username)
  }`;

  try {
    const response = await apiService.request<ResetPasswordParamsType, any>(
      query,
      {
        username: payload.email
      }
    );

    return response.errors
      ? response.errors[0].message
      : response.data.requestPasswordResetToken;
  } catch (error) {
    return (error as Error).message;
  }
}

/**
 * Function to handle confirm reset password.
 * @param {ConfirmResetPasswordPayloadType} payload
 */
async function handleConfirmResetPassword(
  payload: ConfirmResetPasswordPayloadType
) {
  type ConfirmResetPasswordParamsType = {
    token: string;
    password: string;
  };

  const query = `mutation resetPassword($token: String!, $password: String!) {
    resetPassword(token: $token, password: $password)
  }`;

  try {
    const response = await apiService.request<
      ConfirmResetPasswordParamsType,
      any
    >(query, {
      ...payload
    });

    return response.errors
      ? response.errors[0].message
      : response.data.resetPassword;
  } catch (error) {
    return (error as Error).message;
  }
}

/**
 * Function to handle login user.
 * @param {LoginUserPayloadType} payload
 */
async function handleLoginUser(payload: LoginUserPayloadType) {
  type LoginParamsType = {
    username: string;
    passcode: string;
  };

  const variables = ["uid"];

  const query = `mutation login($username: String!, $passcode: String!) {
    login(username: $username, passcode: $passcode) {
      ${variables?.map((variable) => `${variable}`)}
    }
  }`;

  try {
    const response = await apiService.request<LoginParamsType, any>(query, {
      ...payload
    });

    return response.errors ? response.errors[0].message : "OK";
  } catch (error) {
    return (error as Error).message;
  }
}

/**
 * Function to handle login user.
 * @param {AuthLoginUserPayloadType} payload
 */
async function handleAuthLoginUser(payload: AuthLoginUserPayloadType) {
  type LoginParamsType = {
    username: string;
    password: string;
  };

  const query = `mutation loginWithPassword($username: String!, $password: String!) {
    loginWithPassword(username: $username, password: $password) {
      uid
      email
      isEmailVerified
      isOnboardingComplete
    }
  }`;

  try {
    const response = await apiService.request<LoginParamsType, any>(query, {
      ...payload
    });

    return response.errors ? response.errors[0].message : "OK";
  } catch (error) {
    return (error as Error).message;
  }
}

/**
 * Function to handle upload file.
 * @param {UploadFilePayloadType} payload
 */
async function handleUploadFile(payload: UploadFilePayloadType) {
  const query = `mutation($file: Upload, $campaignName: String) {
    uploadDataFile(file: $file, type: ${payload.type}, campaignName: $campaignName)
  }`;

  try {
    const response = await apiService.request<any, any>(
      query,
      {
        file: payload.file,
        campaignName: payload.campaignName
      },
      "apollo"
    );

    return response.errors ? response.errors[0].message : "OK";
  } catch (error) {
    return (error as Error).message;
  }
}

/**
 * Function to handle complete onboarding.
 */
async function handleCompleteOnboarding() {
  const query = `mutation completeOnboarding {
    completeOnboarding
  }`;

  try {
    const response = await apiService.request<any, any>(query);

    return response.errors ? response.errors[0].message : "OK";
  } catch (error) {
    return (error as Error).message;
  }
}

export {
  handleRegisterUser,
  handleLoginUser,
  handleUploadFile,
  handleResetPassword,
  handleCompleteOnboarding,
  handleConfirmResetPassword,
  handleAuthLoginUser
};
