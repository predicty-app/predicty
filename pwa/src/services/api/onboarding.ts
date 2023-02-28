import apiService from '@/services/api/api'

export type RegisterUserPayloadType = {
  email: string
}

/**
 * Function to get bundles list.
 * @param {string} toolSku
 */
async function handleRegisterUser(payload: RegisterUserPayloadType) {
  type RegisterParamsType = {
    email: string
  }

  const query = `mutation register($email: String!) {
    register(email: $email)
  }`

  try {
    const response = await apiService.request<RegisterParamsType, any>(query, {
      email: payload.email
    })

    return response.errors ? response.errors[0].message : response.data.register
  } catch (error) {
    return (error as Error).message
  }
}

export { handleRegisterUser }