<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query;

use App\Repository\UserRepository;
use App\Test\GraphQLTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\GraphQL\Query\MeQuery
 */
class MeQueryTest extends GraphQLTestCase
{
    public function test_me(): void
    {
        $client = static::createClient();
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername('john.doe@example.com');
        assert($user instanceof UserInterface);

        $client->loginUser($user);

        $query = <<<'EOF'
                query {
                  me {
                    uid,
                    email
                  }
                }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/Me.json');
    }
}
