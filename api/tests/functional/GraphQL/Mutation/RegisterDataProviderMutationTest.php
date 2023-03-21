<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Repository\UserRepository;
use App\Test\GraphQLTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\GraphQL\Mutation\RegisterDataProviderMutation
 * @covers \App\Message\CommandHandler\RegisterDataProviderHandler
 */
class RegisterDataProviderMutationTest extends GraphQLTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped();
    }

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

    public function test_register_two_data_providers_in_one_api_call(): void
    {
        $client = static::createClient();
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername('john.doe@example.com');
        assert($user instanceof UserInterface);

        $client->loginUser($user);

        $mutation = <<<'EOF'
                mutation {
                  google: registerDataProvider(type: GOOGLE_ADS, oauthRefreshToken: "123")
                  facebook: registerDataProvider(type: FACEBOOK_ADS, oauthRefreshToken: "123")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMultipleDataProvidersSuccess.json');
    }
}
