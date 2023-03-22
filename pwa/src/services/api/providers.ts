import apiService from "@/services/api/api";

export type ProviderType = {
  id: string
  name: string
  fileImportTypes: string[]
} 

/**
 * Function to handle providers list.
 */
async function handleGetProvidersList() {
  const query = `query providers {
    dataProviders {
      id
      name
      fileImportTypes
    }
  }`;

  type ProvidersType = {
    data: {
      dataProviders: ProviderType[]
    }
  };

  try {
    const response = await apiService.request<ProvidersType, any>(
      query
    );

    return response.errors ? null : response.data.dataProviders;
  } catch (error) {
    return null;
  }
}

export { handleGetProvidersList };