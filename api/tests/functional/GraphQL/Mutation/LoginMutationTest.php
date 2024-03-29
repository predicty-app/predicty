<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Repository\UserRepository;
use App\Service\Security\Passcode\CacheBasedPasscodeService;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\LoginMutation
 * @covers \App\MessageHandler\Command\LoginHandler
 */
class LoginMutationTest extends GraphQLTestCase
{
    public function test_login(): void
    {
        $client = $this->createClient();
        $repository = $client->getContainer()->get(UserRepository::class);
        $passcodeService = $client->getContainer()->get(CacheBasedPasscodeService::class);
        $code = $passcodeService->generate($repository->getByUsername('john.doe@example.com'));

        $mutation = <<<"EOF"
                mutation {
                  login(username: "john.doe@example.com", passcode:"$code"){
                    email
                  },
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/LoginMutationSuccess.json');
    }

    public function test_login_with_invalid_username(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  login(username: "john.doe", passcode:"123456"){
                    email
                  },
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/LoginMutationFailedInvalidUsername.json');
    }

    public function test_login_with_empty_password(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  login(username: "john.doe@example.com", passcode:""){
                    email
                  },
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/LoginMutationFailedEmptyPassword.json');
    }

    /**
     * @see https://github.com/symfony/symfony/issues/48021
     */
    public function test_login_is_throttled_after_multiple_failed_attempts(): void
    {
        $this->markTestSkipped('See https://github.com/symfony/symfony/issues/48021');
    }
}
