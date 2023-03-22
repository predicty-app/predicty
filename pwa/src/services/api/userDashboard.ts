import apiService from "@/services/api/api";
import CampaignsService from "@/services/campaigns";
import CollectionService from "@/services/collections";
import { useUserDashboardStore } from "@/stores/userDashboard";
import type { CampaignNonParsedType } from "@/services/campaigns";

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
    }
  }`;

  type ProvidersType = {
    data: {
      dashboard: CampaignNonParsedType[];
    };
  };

  try {
    const response = await apiService.request<ProvidersType, any>(query);
    let campaigns = CampaignsService.parseCampaignsList(
      response.data.dashboard.campaigns
    );
    campaigns = CollectionService.parseCollectionList(
      response.data.dashboard.collections,
      campaigns
    );

    return response.errors ? null : campaigns;
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
            ? userDashboardStore.campaigns[0].adsets.length + 1
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

export {
  handleGetCampaigns,
  handleCreateCollection,
  handleAssignAdToCollection
};
