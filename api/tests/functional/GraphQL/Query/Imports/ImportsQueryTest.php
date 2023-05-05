<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\Imports;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\ImportsQuery
 * @covers \App\GraphQL\Type\FileImportType
 * @covers \App\GraphQL\Type\ImportType
 * @covers \App\GraphQL\Type\ApiImportType
 */
class ImportsQueryTest extends GraphQLTestCase
{
    public function test_get_imports(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
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
                  downloadUrl
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/Imports.json');
    }
}
