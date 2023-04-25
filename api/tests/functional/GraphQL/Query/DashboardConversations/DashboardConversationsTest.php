<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query\DashboardConversations;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\DashboardQuery
 */
class DashboardConversationsTest extends GraphQLTestCase
{
    public function test_get_conversations(): void
    {
        $this->authenticate();

        $query = <<<'EOF'
            query {
              dashboard {
                conversations {
                  id
                  user {
                    id
                  }
                  date
                  color {
                    hex
                    rgb
                  }
                  isRemovable
                  comments {
                    id
                    user {
                      id
                    }
                    comment
                    createdAt
                    changedAt
                    isEditable
                  }
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardConversations1.json');
    }
}
