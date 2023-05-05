<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Authorization;

use App\Service\Security\Authorization\AuthorizationChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\Service\Security\Authorization\AuthorizationChecker
 */
class AuthorizationCheckerTest extends TestCase
{
    public function test_deny_access_unless_granted(): void
    {
        $adm = $this->createMock(AccessDecisionManagerInterface::class);
        $adm->expects($this->once())->method('decide')->willReturn(true);

        $checker = new AuthorizationChecker($adm);
        $checker->denyAccessUnlessGranted($this->createMock(UserInterface::class), 'permission', 'subject');
    }

    public function test_deny_access_unless_granted_denies_access(): void
    {
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Access Denied.');

        $adm = $this->createMock(AccessDecisionManagerInterface::class);
        $adm->expects($this->once())->method('decide')->willReturn(false);

        $checker = new AuthorizationChecker($adm);
        $checker->denyAccessUnlessGranted($this->createMock(UserInterface::class), 'permission', 'subject');
    }

    public function test_deny_access_unless_granted_denies_access_with_custom_message(): void
    {
        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Custom message');

        $adm = $this->createMock(AccessDecisionManagerInterface::class);
        $adm->expects($this->once())->method('decide')->willReturn(false);

        $checker = new AuthorizationChecker($adm);
        $checker->denyAccessUnlessGranted($this->createMock(UserInterface::class), 'permission', 'subject', 'Custom message');
    }

    public function test_is_granted(): void
    {
        $adm = $this->createMock(AccessDecisionManagerInterface::class);
        $adm->expects($this->once())->method('decide')->willReturn(true);

        $checker = new AuthorizationChecker($adm);

        $checker->isGranted($this->createMock(UserInterface::class), 'permission', 'subject');
    }
}
