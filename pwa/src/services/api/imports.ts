import apiService from "@/services/api/api";

export type ImportDataProviderType = {
  id: "GOOGLE_ADS" | "FACEBOOK_ADS" | "GOOGLE_ANALYTICS" | "TIK_TOK" | "OTHER";
  name: string;
  type:
    | "FACEBOOK_CSV"
    | "OTHER_SIMPLIFIED_CSV"
    | "GOOGLE_ANALYTICS_REVENUE"
    | "GOOGLE_ADS_CSV"
    | "OTHER";
};

export type ImportResultType = {
  createdCampaigns: number;
  createdAdSets: number;
  createdAds: number;
  createdAdStats: number;
  createdDailyRevenues: number;
  totalCreated: number;
};

export type ImportType = {
  id: string;
  status: "WAITING" | "IN_PROGRESS" | "COMPLETE" | "FAILED";
  message: string;
  dataProvider: ImportDataProviderType;
  result: ImportResultType;
  startedAt: string;
  completedAt: string;
  __typename: string;
  filename?: string;
};

/**
 * Function to handle imports list.
 */
async function handleGetImports() {
  const query = `query imports {
    imports {
      id
      __typename
      status
      message
      dataProvider {
        name
      }
      result {
        createdCampaigns
        createdAdSets
        createdAds
        createdAdStats
        createdDailyRevenues
        totalCreated
      }
      startedAt
      completedAt
      ... on FileImport {
        filename
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

export { handleGetImports };
