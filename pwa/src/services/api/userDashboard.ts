import apiService from "@/services/api/api";
import { useGlobalStore } from "@/stores/global";
// import type {
//   CampaignType,
//   AdsType,
//   AdsCollection
// } from "@/stores/userDashboard";
import CampaignsService from "@/services/campaigns";
import CollectionService from "@/services/collections";
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
    return null;
  }
}

/**
 * Function to create new collection.
 * @param {ManagementCollectionPayloadType} payload
 */
function handleCreateCollection(
  payload: Pick<ManagementCollectionPayloadType, "ads" | "campaignUid">
) {
  // list.value = list.value.map((campaign: CampaignType) => {
  //   if (campaign.uid === payload.campaignUid) {
  //     const ads = campaign.ads.filter((ad: AdsType) =>
  //       payload.ads.includes(ad.uid)
  //     );
  //     const { first, last } = hFirstAndLastDate([{ ads } as CampaignType]);
  //     campaign.collection.push({
  //       uid: `${Math.random()}`,
  //       name: `Collection ${campaign.collection.length + 1}`,
  //       ads: payload.ads,
  //       start: first,
  //       end: last
  //     });
  //   }
  //   return campaign;
  // });
}

/**
 * Function to assign ad to existing collection.
 * @param {ManagementCollectionPayloadType} payload
 */
function handleAssignAdToCollection(payload: ManagementCollectionPayloadType) {
  // list.value = list.value.map((campaign: CampaignType) => {
  //   if (campaign.uid === payload.campaignUid) {
  //     campaign.collection = campaign.collection.map(
  //       (collection: AdsCollection) => {
  //         if (collection.uid === payload.collectionUid) {
  //           collection.ads = collection.ads.concat(payload.ads);
  //           const ads = campaign.ads.filter((ad: AdsType) =>
  //             collection.ads.includes(ad.uid)
  //           );
  //           const { first, last } = hFirstAndLastDate([
  //             { ads } as CampaignType
  //           ]);
  //           collection.start = first;
  //           collection.end = last;
  //         }
  //         return collection;
  //       }
  //     );
  //   }
  //   return campaign;
  // });
}

export {
  handleGetCampaigns,
  handleCreateCollection,
  handleAssignAdToCollection
};
