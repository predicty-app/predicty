<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\Dashboard;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\DashboardQuery
 * @covers \App\GraphQL\Type\DailyInsightsType
 */
class DashboardDailyInsightsTest extends GraphQLTestCase
{
    public function test_get_daily_insights(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
                dashboard {
                    insights(from: "2023-01-01", to: "2023-01-02") {
                      date
                      results
                      amountSpent
                      clicks
                      impressions
                      leads
                      costPerClick
                      costPerMil
                      costPerResult
                      revenue
                      averageOrderValue
                    }
                }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/DashboardDailyInsights.json');
    }
}
