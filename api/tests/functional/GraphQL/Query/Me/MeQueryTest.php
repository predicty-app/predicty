<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\Me;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\MeQuery
 */
class MeQueryTest extends GraphQLTestCase
{
    public function test_me(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
                query {
                  me {
                    uid
                    email
                    isEmailVerified
                    isOnboardingComplete
                  }
                }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/Me.json');
    }
}
