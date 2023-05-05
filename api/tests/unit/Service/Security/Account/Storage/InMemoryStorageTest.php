<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Account\Storage;

use App\Service\Security\Account\Storage\InMemoryStorage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\Security\Account\Storage\InMemoryStorage
 */
class InMemoryStorageTest extends TestCase
{
    public function test_set(): void
    {
        $storage = new InMemoryStorage();
        $storage->set(1, 2);
        $this->assertSame([1 => 2], $storage->mockData);
    }

    public function test_get(): void
    {
        $storage = new InMemoryStorage();
        $storage->mockData = [1 => 2];
        $this->assertSame(2, $storage->get(1));
    }
}
