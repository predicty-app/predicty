<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Authorization;

use App\Service\Security\Authorization\AuthorizationToken;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\Service\Security\Authorization\AuthorizationToken
 */
class AuthorizationTokenTest extends TestCase
{
    public function test_create(): void
    {
        $token = new AuthorizationToken($this->createMock(UserInterface::class));
        $this->assertInstanceOf(AbstractToken::class, $token);
    }
}
