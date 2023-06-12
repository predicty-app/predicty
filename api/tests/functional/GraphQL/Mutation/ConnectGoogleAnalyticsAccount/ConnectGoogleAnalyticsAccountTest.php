<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\ConnectGoogleAnalyticsAccount;

use App\Entity\GoogleAnalyticsConnectedAccount;
use App\Message\Event\AccountConnected;
use App\Test\GraphQLTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\ConnectGoogleAnalyticsAccountMutation
 * @covers \App\MessageHandler\Command\ConnectGoogleAnalyticsHandler
 */
class ConnectGoogleAnalyticsAccountTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_connect(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  connectGoogleAnalyticsAccount(oauthRefreshToken: "123", ga4id: "1254")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ConnectGoogleAnalyticsAccountSuccess.json');

        $connectedAccount = $this->getRepository(GoogleAnalyticsConnectedAccount::class)->findOneBy([], ['id' => 'DESC']);
        $this->assertInstanceOf(GoogleAnalyticsConnectedAccount::class, $connectedAccount);

        $this->assertSame('123', $connectedAccount->getRefreshToken());
        $this->assertSame('1254', $connectedAccount->getGa4Id());
        $this->assertTrue($connectedAccount->isEnabled());

        $this->bus('event.bus')->dispatched()->assertContains(AccountConnected::class);
    }
}
