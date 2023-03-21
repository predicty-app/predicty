<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\DashboardQuery
 */
class DashboardDailyRevenueTest extends GraphQLTestCase
{
    /**
     * @covers \App\GraphQL\Type\DailyRevenueType
     */
    public function test_get_dashboard_campaign_ad_revenue_share(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                    dailyRevenue {
                  date
                  revenue {
                    amount
                  }
                  averageOrderValue {
                    amount
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardDailyRevenue.json');
    }
}
