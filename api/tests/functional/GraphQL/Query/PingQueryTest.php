<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query;

use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Query\PingQuery
 */
class PingQueryTest extends GraphQLTestCase
{
    public function test_ping(): void
    {
        $mutation = <<<'EOF'
                query {
                  ping
                }
            EOF;

        $this->executeQuery($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/Ping.json');
    }
}
