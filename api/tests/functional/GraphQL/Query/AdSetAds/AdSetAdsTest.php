<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\AdSetAds;

use App\Test\GraphQLTestCase;

/**
 * @coversNothing
 */
class AdSetAdsTest extends GraphQLTestCase
{
    public function test_get_adset_ads_complete_data(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                campaigns {
                  name
                  adSets {
                    name
                    ads {
                      id
                      externalId
                      name
                      campaignId
                      startedAt
                      endedAt
                    }
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/GetAdSetAdsTest2.json');
    }

    public function test_get_adset_ads_in_order_from_the_oldest_to_newest(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                campaigns {
                  adSets {
                    ads {
                      id
                      startedAt
                      endedAt
                    }
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/GetAdSetAdsTest1.json');
    }
}
