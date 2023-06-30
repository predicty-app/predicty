<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\Dashboard;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\DashboardQuery
 * @covers \App\GraphQL\Type\DailyInsightsType
 */
class DashboardAdInsightsTest extends GraphQLTestCase
{
    public function test_get_ad_insights(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                campaigns {
                  adSets {
                    ads {
                      adInsights {
                        id
                        results
                        costPerResult {
                          amount
                          currency
                        }
                        amountSpent {
                          amount
                          currency
                        }
                        revenueShare {
                          amount
                          currency
                        }
                        date
                      }
                    }
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardAdInsights.json');
    }
}
