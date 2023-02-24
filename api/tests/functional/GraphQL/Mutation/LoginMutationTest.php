<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\LoginMutation
 * @covers \App\MessageHandler\Command\LoginHandler
 */
class LoginMutationTest extends GraphQLTestCase
{
    public function test_login(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  login(username: "john.doe@example.com", password:"123456"){
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
                  login(username: "john.doe", password:"123456"){
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
                  login(username: "john.doe@example.com", password:""){
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