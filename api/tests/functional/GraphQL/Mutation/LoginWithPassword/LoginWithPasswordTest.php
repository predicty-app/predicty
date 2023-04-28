<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\LoginWithPassword;

use App\Test\GraphQLTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\ChangePasswordMutation
 * @covers \App\MessageHandler\Command\ChangePasswordHandler
 */
class LoginWithPasswordTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_login_with_password_success(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  loginWithPassword(username: "john.doe@example.com", password: "123456") {
                    email
                  }
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/LoginWithPasswordSuccess.json');
    }

    public function test_login_with_password_using_invalid_email(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  loginWithPassword(username: "john.doe.not.existing@example.com", password: "123456") {
                    email
                  }
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/LoginWithPasswordFailure1.json');
    }

    public function test_login_with_password_using_invalid_password(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  loginWithPassword(username: "john.doe@example.com", password: "asdf123") {
                    email
                  }
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/LoginWithPasswordFailure1.json');
    }
}
