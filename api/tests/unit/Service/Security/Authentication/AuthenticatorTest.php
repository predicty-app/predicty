<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Authentication;

use App\Entity\User;
use App\Service\Security\Authentication\AuthenticationResultListener;
use App\Service\Security\Authentication\Authenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface;

/**
 * @covers \App\Service\Security\Authentication\Authenticator
 */
class AuthenticatorTest extends TestCase
{
    public function test_authenticate_with_passcode(): void
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag();

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getMainRequest')->willReturn($request);

        $authenticatorManager = $this->createMock(AuthenticatorManagerInterface::class);
        $authenticatorManager->expects($this->once())->method('supports');
        $authenticatorManager->expects($this->once())->method('authenticateRequest');

        $resultListener = $this->createMock(AuthenticationResultListener::class);
        $resultListener->method('getResult')->willReturn($this->createMock(User::class));

        $security = $this->createMock(Security::class);

        $authenticator = new Authenticator(
            $requestStack,
            $authenticatorManager,
            $resultListener,
            $security
        );

        $result = $authenticator->authenticateWithPasscode('username', '123456');
        $this->assertInstanceOf(User::class, $result);

        $this->assertSame([
            'username' => 'username',
            'passcode' => '123456',
        ], $request->attributes->get('_graphql_credentials'));
    }

    public function test_authenticate_with_password(): void
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag();

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getMainRequest')->willReturn($request);

        $authenticatorManager = $this->createMock(AuthenticatorManagerInterface::class);
        $authenticatorManager->expects($this->once())->method('supports');
        $authenticatorManager->expects($this->once())->method('authenticateRequest');

        $resultListener = $this->createMock(AuthenticationResultListener::class);
        $resultListener->method('getResult')->willReturn($this->createMock(User::class));

        $security = $this->createMock(Security::class);

        $authenticator = new Authenticator(
            $requestStack,
            $authenticatorManager,
            $resultListener,
            $security
        );

        $result = $authenticator->authenticateWithPassword('username', 'qwerty123');
        $this->assertInstanceOf(User::class, $result);

        $this->assertSame([
            'username' => 'username',
            'password' => 'qwerty123',
        ], $request->attributes->get('_graphql_credentials'));
    }
}
