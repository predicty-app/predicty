<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\LogoutMutation
 * @covers \App\Message\CommandHandler\LogoutHandler
 */
class LogoutMutationTest extends GraphQLTestCase
{
    public function test_logout(): void
    {
        $this->authenticate();

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
