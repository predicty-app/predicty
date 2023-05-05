<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security;

use App\Entity\User;
use App\Service\Security\UserRoleUpdater;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * @covers \App\Service\Security\UserRoleUpdater
 */
class UserRoleUpdaterTest extends TestCase
{
    public function test_update(): void
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('setToken')
            ->with($this->isInstanceOf(PostAuthenticationToken::class))
            ->willReturnCallback(
                function (PostAuthenticationToken $token): void {
                    $this->assertSame('main', $token->getFirewallName());
                    $this->assertSame(['ROLE_USER', 'ROLE_SOMETHING'], $token->getRoleNames());
                }
            );

        $user = $this->createMock(User::class);
        $user->method('getRoles')->willReturn(['ROLE_USER', 'ROLE_SOMETHING']);

        $updater = new UserRoleUpdater($tokenStorage);
        $updater->update($user);
    }
}
