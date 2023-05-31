<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Account\Storage;

use App\Service\Security\Account\Storage\SessionStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\Security\Account\Storage\SessionStorage
 */
class SessionStorageTest extends TestCase
{
    public function test_get(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $session = $this->createMock(SessionInterface::class);
        $session->method('get')->with('account_switcher', ['user_id' => null, 'account_id' => null])
            ->willReturn(['user_id' => (string) $userId, 'account_id' => (string) $accountId]);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($session);

        $storage = new SessionStorage($requestStack);
        $this->assertEquals($accountId, $storage->get($userId));
    }

    public function test_set(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $session = $this->createMock(SessionInterface::class);
        $session->expects($this->once())->method('set')->with('account_switcher', [
            'user_id' => '01H1VECDYVB5BRQVPTSVJP3BZA',
            'account_id' => '01H1VEC8SYM3K6TSDAPFN25XZV',
        ]);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($session);

        $storage = new SessionStorage($requestStack);
        $storage->set($userId, $accountId);
    }
}
