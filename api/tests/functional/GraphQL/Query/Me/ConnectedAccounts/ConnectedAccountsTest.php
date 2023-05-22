<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\Me\ConnectedAccounts;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Type\UserType
 * @covers \App\GraphQL\Type\ConnectedAccountType
 */
class ConnectedAccountsTest extends GraphQLTestCase
{
    public function test_connected_accounts(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              me {
                account {
                  connectedAccounts {
                    id
                    isEnabled
                    connectedAt
                    dataProvider {
                      id
                    }
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/ConnectedAccounts1.json');
    }
}
