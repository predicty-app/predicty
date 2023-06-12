<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Account\Storage;

use App\Service\Security\Account\Storage\InMemoryStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\Security\Account\Storage\InMemoryStorage
 */
class InMemoryStorageTest extends TestCase
{
    public function test_set(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $storage = new InMemoryStorage();
        $storage->set($userId, $accountId);
        $this->assertSame([(string) $userId => $accountId], $storage->mockData);
    }

    public function test_get(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $storage = new InMemoryStorage();
        $storage->mockData = [(string) $userId => $accountId];
        $this->assertSame($accountId, $storage->get($userId));
    }
}
