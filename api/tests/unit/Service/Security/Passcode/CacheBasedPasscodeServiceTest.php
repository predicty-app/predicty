<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Passcode;

use App\Entity\DoctrineUser;
use App\Service\Security\Passcode\CacheBasedPasscodeService;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\Security\Passcode\CacheBasedPasscodeService
 */
class CacheBasedPasscodeServiceTest extends TestCase
{
    public function test_generate(): void
    {
        $user = $this->createMock(DoctrineUser::class);
        $user->method('getId')->willReturn(Ulid::fromString('01H1VA0J0ZVV764BC237KBW8J2'));

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('set');

        $service = new CacheBasedPasscodeService($cache);
        $service->generate($user);
    }

    public function test_verify(): void
    {
        $user = $this->createMock(DoctrineUser::class);
        $user->method('getId')->willReturn(Ulid::fromString('01H1VA0J0ZVV764BC237KBW8J2'));

        $storedKey = null;
        $storedValue = null;

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())
            ->method('set')
            ->willReturnCallback(function (string $key, string $value) use (&$storedKey, &$storedValue) {
                $storedKey = $key;
                $storedValue = $value;

                return true;
            });

        $service = new CacheBasedPasscodeService($cache);
        $code = $service->generate($user);

        $cache->expects($this->once())->method('get')->with($storedKey)->willReturn($storedValue);
        $cache->expects($this->once())->method('delete')->with($storedKey);

        $this->assertTrue($service->isPasscodeValid($user, $code));
    }

    public function test_verify_default_passcode_on_dev_environment(): void
    {
        $user = $this->createMock(DoctrineUser::class);
        $cache = $this->createMock(CacheInterface::class);
        $service = new CacheBasedPasscodeService($cache, 'dev');

        $this->assertTrue($service->isPasscodeValid($user, '111111'));
    }
}
