<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Account\Storage;

use App\Service\Security\Account\Storage\SessionStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @covers \App\Service\Security\Account\Storage\SessionStorage
 */
class SessionStorageTest extends TestCase
{
    public function test_get(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $session->method('get')->with('account_switcher', ['user_id' => null, 'account_id' => null])
            ->willReturn(['user_id' => 1, 'account_id' => 123]);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($session);

        $storage = new SessionStorage($requestStack);
        $this->assertSame(123, $storage->get(1));
    }

    public function test_set(): void
    {
        $session = $this->createMock(SessionInterface::class);
        $session->expects($this->once())->method('set')->with('account_switcher', ['user_id' => 1, 'account_id' => 456]);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($session);

        $storage = new SessionStorage($requestStack);
        $storage->set(1, 456);
    }
}
