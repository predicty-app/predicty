<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\DataProvidersQuery
 */
class DataProvidersTest extends GraphQLTestCase
{
    public function test_get_all_global_data_providers(): void
    {
        $mutation = <<<'EOF'
            query {
              dataProviders {
                id
                type
                name
                fileImportTypes
                createdAt
              }
            }
            EOF;

        $this->executeQuery($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DataProviders.json');
    }

    public function test_get_global_and_custom_data_providers(): void
    {
        $this->authenticate();
        $mutation = <<<'EOF'
            query {
              dataProviders {
                id
                type
                name
                fileImportTypes
                createdAt
              }
            }
            EOF;

        $this->executeQuery($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/CustomDataProviders.json');
    }
}
