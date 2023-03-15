import apiService from "@/services/api/api";
import { FilesTypes } from "@/stores/onboarding";

export type RegisterUserPayloadType = {
  email: string;
};

export type LoginUserPayloadType = {
  username: string;
  passcode: string;
};

export type UploadFilePayloadType = {
  type: FilesTypes;
  file: File;
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
 * Function to handle upload file.
 * @param {UploadFilePayloadType} payload
 */
async function handleUploadFile(payload: UploadFilePayloadType) {
  const query = `mutation($file: Upload) {
    uploadDataFile(file: $file, type: ${payload.type})
  }`;

  try {
    const response = await apiService.request<any, any>(
      query,
      {
        file: payload.file
      },
      "apollo"
    );

    return response.errors ? response.errors[0].message : "OK";
  } catch (error) {
    return (error as Error).message;
  }
}

export { handleRegisterUser, handleLoginUser, handleUploadFile };
