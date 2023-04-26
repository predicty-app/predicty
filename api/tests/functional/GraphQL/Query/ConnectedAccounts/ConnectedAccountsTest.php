<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\ConnectedAccounts;

use App\Repository\UserRepository;
use App\Test\GraphQLTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\GraphQL\Type\UserType
 * @covers \App\GraphQL\Type\ConnectedAccountType
 */
class ConnectedAccountsTest extends GraphQLTestCase
{
    public function test_connected_accounts(): void
    {
        $client = static::createClient();
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername('john.doe@example.com');
        assert($user instanceof UserInterface);

        $client->loginUser($user);

        $query = <<<'EOF'
            query {
              me {
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
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/ConnectedAccounts1.json');
    }
}
