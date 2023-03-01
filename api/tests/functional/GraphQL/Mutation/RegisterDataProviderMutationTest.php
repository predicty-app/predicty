<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Repository\UserRepository;
use App\Test\GraphQLTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\GraphQL\Mutation\RegisterDataProviderMutation
 * @covers \App\MessageHandler\Command\RegisterDataProviderHandler
 */
class RegisterDataProviderMutationTest extends GraphQLTestCase
{
    public function test_register_data_provider(): void
    {
        $client = static::createClient();
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername('john.doe@example.com');
        assert($user instanceof UserInterface);

        $client->loginUser($user);

        $mutation = <<<'EOF'
                mutation {
                  registerDataProvider(type: GOOGLE_ADS, oauthRefreshToken: "123")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterDataProviderSuccess.json');
    }
}
