<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\Dashboard;

use App\DataFixtures\UserFixture;
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
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardDetails.json');
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
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardCollections.json');
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
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardCampaigns.json');
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
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardCampaignAds.json');
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
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardCampaignAdSets.json');
    }

    /**
     * @covers \App\GraphQL\Type\DailyRevenueType
     */
    public function test_get_dashboard_campaign_ad_revenue_share(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                campaigns {
                        adSets{
                    ads{
                      adStats{
                        revenueShare {
                          amount
                          currency
                        }
                      }
                    }
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardAdRevenueShare.json');
    }

    /**
     * @covers \App\GraphQL\Type\DailyRevenueType
     */
    public function test_get_dashboard_as_other_account_user(): void
    {
        $this->authenticate(UserFixture::JANE);

        $query = <<<'EOF'
            query {
              dashboard {
                campaigns {
                        adSets{
                    ads{
                      adStats{
                        revenueShare {
                          amount
                          currency
                        }
                      }
                    }
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardAdRevenueShare.json');
    }

    public function test_user_without_account_access_cannot_see_other_users_dashboard_data(): void
    {
        $this->authenticate(UserFixture::ANDREW);

        $query = <<<'EOF'
            query {
                dashboard {
                name,
                dailyRevenue {
                  id
                }
                campaigns {
                  name
                }
                collections {
                  name
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/EmptyDashboard.json');
    }
}
