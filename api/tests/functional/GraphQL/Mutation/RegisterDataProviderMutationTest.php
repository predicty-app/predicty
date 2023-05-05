<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\RegisterDataProviderMutation
 * @covers \App\MessageHandler\Command\RegisterGoogleOAuthCredentialsHandler
 */
class RegisterDataProviderMutationTest extends GraphQLTestCase
{
    public function test_register_data_provider(): void
    {
        $this->authenticate();

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
        $this->authenticate();

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
