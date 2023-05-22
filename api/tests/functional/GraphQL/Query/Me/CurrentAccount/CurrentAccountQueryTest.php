<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\Me\CurrentAccount;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\MeQuery
 */
class CurrentAccountQueryTest extends GraphQLTestCase
{
    public function test_current_account(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              me {
                account {
                  id
                  name
                  connectedAccounts {
                    id
                  }
                  users {
                    id
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/Account.json');
    }

    public function test_current_account_actions(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              me {
                actions {
                  hasToAcceptTermsOfService
                  hasToVerifyEmail
                  hasToCompleteOnboarding
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/AccountActions.json');
    }
}
