<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\CreateAdCollectionMutation
 * @covers \App\MessageHandler\Command\CreateAdCollectionHandler
 */
class CreateAdCollectionMutationTest extends GraphQLTestCase
{
    public function test_create_ad_collection(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
            mutation {
              createAdCollection(name: "Test") {
                id
                name
              }
            }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/CreateAdCollection.json');
    }
}
