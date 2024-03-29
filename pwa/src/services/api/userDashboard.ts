import apiService from "@/services/api/api";
import CampaignsService from "@/services/campaigns";
import { useUserDashboardStore } from "@/stores/userDashboard";

type ManagementCollectionPayloadType = {
  campaignUid: string;
  ads: string[];
  collectionUid: string;
};

/**
 * Function to get all campaigns user.
 */
async function handleGetCampaigns() {
  const query = `query Dashboard {
    dashboard {
      name
      dailyRevenue {
        id
        revenue {
          amount
          currency
        }
        averageOrderValue {
          amount
          currency
        }
        date
      }
      campaigns {
        id
        externalId
        dataProvider {
          id
        }
        name
        adSets {
          name
          id
          endedAt
          startedAt
          externalId
          ads {
            id
            name
            adStats {
              amountSpent {
                currency
                amount
              }
              results
              costPerResult {
                amount
                currency
              }
              revenueShare {
                amount
                currency
              }
              date
              id
            }
            externalId
          }
        }
      }
      collections{
        id
        name
        ads {
          id
          name
          externalId
          campaignId
          adStats {
            amountSpent {
              currency
              amount
            }
            results
            costPerResult {
              amount
              currency
            }
            revenueShare {
              amount
              currency
            }
            date
            id
          }
        }
      }
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

    const campaigns = await CampaignsService.createCollectionList(
      response.data.dashboard.campaigns,
      response.data.dashboard.collections
    );

    return response.errors
      ? null
      : {
          campaigns,
          dailyRevenue: response.data.dashboard.dailyRevenue,
          conversations: response.data.dashboard.conversations
        };
  } catch (error) {
    console.log(error);
    return null;
  }
}

/**
 * Function to create new collection.
 * @param {ManagementCollectionPayloadType} payload
 */
async function handleCreateCollection(
  payload: Pick<ManagementCollectionPayloadType, "ads" | "campaignUid">
) {
  type CreateAdCollectionType = {
    name: string;
  };

  const query = `mutation createAdCollection($name: String) {
    createAdCollection(name: $name) {
      id
    }
  }`;

  const userDashboardStore = useUserDashboardStore();

  try {
    const response = await apiService.request<CreateAdCollectionType, any>(
      query,
      {
        name: `Collection ${
          userDashboardStore.campaigns[0].isCollection
            ? userDashboardStore.campaigns[0].adSets.length + 1
            : 1
        }`
      }
    );

    if (!response.errors) {
      await handleAssignAdToCollection({
        collectionUid: response.data.createAdCollection.id,
        ...payload
      });
    }

    return response.errors ? null : true;
  } catch (error) {
    return null;
  }
}

/**
 * Function to assign ad to existing collection.
 * @param {ManagementCollectionPayloadType} payload
 */
async function handleAssignAdToCollection(
  payload: ManagementCollectionPayloadType
) {
  type AssignAdsCollectionType = {
    adCollectionId: string;
    adsIds: string[];
  };

  const query = `mutation addToAdCollection($adCollectionId: ID!, $adsIds: [ID]!){
    addToAdCollection(adCollectionId: $adCollectionId, adsIds: $adsIds) {
      id
    }
  }`;

  try {
    const response = await apiService.request<AssignAdsCollectionType, any>(
      query,
      {
        adCollectionId: payload.collectionUid,
        adsIds: payload.ads
      }
    );

    return response.errors ? null : true;
  } catch (error) {
    return null;
  }
}

/**
 * Function to un assign ad from existing collection.
 * @param {ManagementCollectionPayloadType} payload
 */
async function handleUnAssignAdFromCollection(
  payload: ManagementCollectionPayloadType
) {
  type AssignAdsCollectionType = {
    adCollectionId: string;
    adsIds: string[];
  };

  const query = `mutation removeAdFromCollection($adsIds: [ID]!, $adCollectionId: ID!) {
    removeAdFromCollection(adsIds: $adsIds, adCollectionId: $adCollectionId) {
      id
    }
  }`;

  try {
    const response = await apiService.request<AssignAdsCollectionType, any>(
      query,
      {
        adCollectionId: payload.collectionUid,
        adsIds: payload.ads
      }
    );

    return response.errors ? null : true;
  } catch (error) {
    return null;
  }
}

export {
  handleGetCampaigns,
  handleCreateCollection,
  handleAssignAdToCollection,
  handleUnAssignAdFromCollection
};
