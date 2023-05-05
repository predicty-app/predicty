<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\PasswordReset;

use App\Entity\User;
use App\Service\Security\PasswordReset\PasswordResetService;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * @covers \App\Service\Security\PasswordReset\PasswordResetService
 */
class PasswordResetServiceTest extends TestCase
{
    public function test_create_token(): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getId')->willReturn(1);

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('set')->with($this->matchesRegularExpression('#^PasswordResetService_.*$#'), 1, 3600);

        $service = new PasswordResetService('secret', $cache);
        $service->createToken($user);
    }

    public function test_validate_and_get_user_id(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('get')->with('PasswordResetService_token123')->willReturn(123);
        $cache->expects($this->once())->method('delete')->with('PasswordResetService_token123');

        $service = new PasswordResetService('secret', $cache);
        $this->assertSame(123, $service->validateAndGetUserId('token123'));
    }

    public function test_validate_and_get_not_existing_token(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('get')->with('PasswordResetService_token123')->willReturn(null);
        $cache->expects($this->never())->method('delete')->with('PasswordResetService_token123');

        $service = new PasswordResetService('secret', $cache);
        $this->assertNull($service->validateAndGetUserId('token123'));
    }
}
