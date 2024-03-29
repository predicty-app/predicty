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
                isEmailVerified
                isOnboardingComplete
                hasAgreedToNewsletter
                hasAcceptedTermsOfService
                hasVerifiedEmail
                hasCompletedOnboarding
                acceptedTermsOfServiceVersion
                account {
                  id
                  name
                }
                actions {
                  hasToAcceptTermsOfService
                  hasToVerifyEmail
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/Me.json');
    }
}
