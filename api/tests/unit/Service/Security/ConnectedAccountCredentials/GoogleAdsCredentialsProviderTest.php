<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\ConnectedAccountCredentials;

use App\Entity\GoogleAdsConnectedAccount;
use App\Repository\ConnectedAccountRepository;
use App\Service\Security\ConnectedAccountCredentials\GoogleAdsCredentialsProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\Security\ConnectedAccountCredentials\GoogleAdsCredentialsProvider
 */
class GoogleAdsCredentialsProviderTest extends TestCase
{
    public function test_get_developer_token(): void
    {
        $repository = $this->createMock(ConnectedAccountRepository::class);
        $provider = new GoogleAdsCredentialsProvider('test', $repository);
        $this->assertSame('test', $provider->getDeveloperToken());
    }

    public function test_get_credentials(): void
    {
        $connectedAccountId = Ulid::fromString('01H26NCW4DG3G87ZJ673BPDB5S');
        $credentials = $this->createMock(GoogleAdsConnectedAccount::class);
        $repository = $this->createMock(ConnectedAccountRepository::class);
        $repository->expects($this->once())->method('findById')->with($connectedAccountId)->willReturn($credentials);

        $provider = new GoogleAdsCredentialsProvider('test', $repository);

        $this->assertSame($credentials, $provider->getCredentials($connectedAccountId));
    }
}
