<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\RegisterMutation
 * @covers \App\MessageHandler\Command\RegisterHandler
 */
class RegisterMutationTest extends GraphQLTestCase
{
    public function test_register(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com", password:"123456")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMutationSuccess.json');
    }

    public function test_register_returns_error_if_email_is_already_used(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe@example.com", password:"123456")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMutationFailedEmailAlreadyUsed.json');
    }

    public function test_register_returns_error_if_invalid_email_is_used(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "asdf", password:"123456")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMutationFailedInvalidEmail.json');
    }

    public function test_register_returns_error_if_empty_email_is_used(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "", password:"123456")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMutationFailedEmptyEmail.json');
    }
}
