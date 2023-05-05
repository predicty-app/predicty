<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Authorization;

use App\Service\Security\Authorization\AuthorizationChecker;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\Service\Security\Authorization\AuthorizationCheckerTrait
 */
class AuthorizationCheckerTraitTest extends TestCase
{
    public function test_is_granted(): void
    {
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);
        $authorizationChecker->expects($this->once())->method('isGranted')->willReturn(true);

        $trait = $this->getMockForTrait(AuthorizationCheckerTrait::class);
        /* @phpstan-ignore-next-line */
        $trait->setAuthorizationChecker($authorizationChecker);

        /* @phpstan-ignore-next-line */
        $trait->isGranted($this->createMock(UserInterface::class), 'permission', 'subject');
    }

    public function test_set_authorization_checker(): void
    {
        $trait = $this->getMockForTrait(AuthorizationCheckerTrait::class);
        /* @phpstan-ignore-next-line */
        $trait->setAuthorizationChecker($this->createMock(AuthorizationChecker::class));
        $this->addToAssertionCount(1);
    }

    public function test_deny_access_unless_granted(): void
    {
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);
        $authorizationChecker->expects($this->once())->method('denyAccessUnlessGranted');

        $trait = $this->getMockForTrait(AuthorizationCheckerTrait::class);
        /* @phpstan-ignore-next-line */
        $trait->setAuthorizationChecker($authorizationChecker);

        /* @phpstan-ignore-next-line */
        $trait->denyAccessUnlessGranted($this->createMock(UserInterface::class), 'permission', 'subject');
    }
}
