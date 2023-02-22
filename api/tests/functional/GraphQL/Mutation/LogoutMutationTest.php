<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Repository\UserRepository;
use App\Test\GraphQLTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\GraphQL\Mutation\LogoutMutation
 * @covers \App\MessageHandler\Command\LogoutHandler
 */
class LogoutMutationTest extends GraphQLTestCase
{
    public function test_logout(): void
    {
        $client = static::createClient();
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername('john.doe@example.com');
        assert($user instanceof UserInterface);

        $client->loginUser($user);

        $mutation = <<<'EOF'
                mutation {
                  logout
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/LogoutMutationSuccess.json');
    }

    public function test_logout_when_no_user_logged_in(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  logout
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/LogoutMutationNoUserSuccess.json');
    }
}
