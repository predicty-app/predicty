import apiService from "@/services/api/api";
import { useGlobalStore } from "@/stores/global";

/**
 * Function to handle app settings.
 */
async function handleGetAppSettings() {
  const query = `query App{
    app {
      version
      currentTermsOfServiceVersion
    }
  }`;

  try {
    const globalStore = useGlobalStore();
    const response = await apiService.request<any, any>(query, {});

    globalStore.version = response.data.app.version;
    globalStore.currentTermsOfServiceVersion = response.data.app.currentTermsOfServiceVersion;

    return 'OK';
  } catch (error) {
    return (error as Error).message;
  }
}

export { handleGetAppSettings };