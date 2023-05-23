<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\ConnectGoogleAdsAccount;

use App\Entity\GoogleAdsConnectedAccount;
use App\Message\Event\AccountConnected;
use App\Test\GraphQLTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\ConnectGoogleAdsAccountMutation
 * @covers \App\MessageHandler\Command\ConnectGoogleAdsHandler
 */
class ConnectGoogleAdsAccountTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_connect(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  connectGoogleAdsAccount(oauthRefreshToken: "123", customerId: "01H28697N4CF8YNVE38BJZDEV6")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ConnectGoogleAdsAccountSuccess.json');

        $connectedAccount = $this->getRepository(GoogleAdsConnectedAccount::class)->findOneBy([], ['id' => 'DESC']);
        $this->assertInstanceOf(GoogleAdsConnectedAccount::class, $connectedAccount);

        $this->assertSame('123', $connectedAccount->getRefreshToken());
        $this->assertSame('01H28697N4CF8YNVE38BJZDEV6', $connectedAccount->getCustomerId());
        $this->assertTrue($connectedAccount->isEnabled());

        $this->bus('event.bus')->dispatched()->assertContains(AccountConnected::class);
    }
}
