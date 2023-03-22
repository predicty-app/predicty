<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\DashboardQuery
 */
class DashboardQueryTest extends GraphQLTestCase
{
    public function test_get_dashboard_details(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                name,
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardDetails.json');
    }

    /**
     * @covers \App\GraphQL\Type\AdCollectionType
     */
    public function test_get_dashboard_collections(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                collections {
                  id,
                  name,
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardCollections.json');
    }

    /**
     * @covers \App\GraphQL\Type\CampaignType
     */
    public function test_get_dashboard_campaigns(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                 campaigns{
                  id,
                  name,
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardCampaigns.json');
    }

    /**
     * @covers \App\GraphQL\Type\AdType
     */
    public function test_get_dashboard_campaign_ads(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                campaigns {
                  adSets {
                    ads {
                      id
                      externalId
                      name
                      campaignId
                      adStats {
                        id
                      }
                    }
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardCampaignAds.json');
    }

    /**
     * @covers \App\GraphQL\Type\AdSetType
     */
    public function test_get_dashboard_campaign_ad_sets(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                campaigns {
                  adSets {
                    id
                    externalId
                    name
                    startedAt
                    endedAt
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardCampaignAdSets.json');
    }
}
