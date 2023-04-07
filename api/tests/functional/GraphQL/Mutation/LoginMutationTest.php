<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Repository\UserRepository;
use App\Service\Security\Passcode\CacheBasedPasscodeService;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\LoginMutation
 * @covers \App\Message\CommandHandler\LoginHandler
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
                    uid,
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
                    uid,
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
                    uid,
                    email
                  },
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/LoginMutationFailedEmptyPassword.json');
    }
}
