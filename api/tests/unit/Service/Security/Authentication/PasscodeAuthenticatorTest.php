<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Authentication;

use App\Entity\User;
use App\Service\Security\Authentication\PasscodeAuthenticator;
use App\Service\Security\Passcode\PasscodeVerifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;

/**
 * @covers \App\Service\Security\Authentication\PasscodeAuthenticator
 * @covers \App\Service\Security\Authentication\GraphQLAuthenticator
 */
class PasscodeAuthenticatorTest extends TestCase
{
    public function test_supports(): void
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag([
            '_graphql_credentials' => ['username' => 'asdf', 'passcode' => 'asdf'],
        ]);

        $verifier = $this->createMock(PasscodeVerifier::class);
        $authenticator = new PasscodeAuthenticator($verifier);

        $this->assertTrue($authenticator->supports($request));

        $request->attributes = new ParameterBag([
            '_graphql_credentials' => ['username' => 'asdf', 'password' => 'asdf'],
        ]);

        $this->assertFalse($authenticator->supports($request));
    }

    public function test_authenticate(): void
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag([
            '_graphql_credentials' => ['username' => 'username', 'passcode' => '123456'],
        ]);

        $verifier = $this->createMock(PasscodeVerifier::class);
        $verifier->expects($this->once())->method('isPasscodeValid')->with($this->isInstanceOf(User::class), '123456')->willReturn(true);

        $authenticator = new PasscodeAuthenticator($verifier);
        $passport = $authenticator->authenticate($request);

        /** @var CustomCredentials $badge */
        $badge = $passport->getBadge(CustomCredentials::class);
        $this->assertInstanceOf(CustomCredentials::class, $badge);
        $badge->executeCustomChecker($this->createMock(User::class));
    }
}
