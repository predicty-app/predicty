<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\ConnectedAccountCredentials;

use App\Entity\ConnectedAccount;
use App\Entity\DataProvider;
use App\Repository\ConnectedAccountRepository;
use App\Service\Security\ConnectedAccountCredentials\ConnectedAccountCredentialsProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\Security\ConnectedAccountCredentials\ConnectedAccountCredentialsProvider
 */
class ConnectedAccountCredentialsProviderTest extends TestCase
{
    public function test_get_credentials(): void
    {
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $repository = $this->createMock(ConnectedAccountRepository::class);
        $repository->expects($this->once())->method('find')->with($accountId, DataProvider::OTHER)->willReturn($this->createMock(ConnectedAccount::class));
        $provider = new ConnectedAccountCredentialsProvider($repository);

        $provider->getCredentials($accountId, DataProvider::OTHER);
    }

    public function test_get_credentials_throws_exception_if_no_credentials_were_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Credentials for given user and data provider were not found.');

        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $repository = $this->createMock(ConnectedAccountRepository::class);
        $repository->expects($this->once())->method('find')->willReturn(null);
        $provider = new ConnectedAccountCredentialsProvider($repository);

        $provider->getCredentials($accountId, DataProvider::OTHER);
    }
}
