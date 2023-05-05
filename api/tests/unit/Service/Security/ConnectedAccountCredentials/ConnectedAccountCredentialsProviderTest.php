<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\ConnectedAccountCredentials;

use App\Entity\ConnectedAccount;
use App\Entity\DataProvider;
use App\Repository\ConnectedAccountRepository;
use App\Service\Security\ConnectedAccountCredentials\ConnectedAccountCredentialsProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \App\Service\Security\ConnectedAccountCredentials\ConnectedAccountCredentialsProvider
 */
class ConnectedAccountCredentialsProviderTest extends TestCase
{
    public function test_get_credentials(): void
    {
        $repository = $this->createMock(ConnectedAccountRepository::class);
        $repository->expects($this->once())->method('find')->with(1, DataProvider::OTHER)->willReturn($this->createMock(ConnectedAccount::class));
        $provider = new ConnectedAccountCredentialsProvider($repository);

        $provider->getCredentials(1, DataProvider::OTHER);
    }

    public function test_get_credentials_throws_exception_if_no_credentials_were_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Credentials for given user and data provider were not found.');

        $repository = $this->createMock(ConnectedAccountRepository::class);
        $repository->expects($this->once())->method('find')->with(1, DataProvider::OTHER)->willReturn(null);
        $provider = new ConnectedAccountCredentialsProvider($repository);

        $provider->getCredentials(1, DataProvider::OTHER);
    }
}
