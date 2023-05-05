<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Authentication;

use App\Service\Security\Authentication\PasswordAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

/**
 * @covers \App\Service\Security\Authentication\PasswordAuthenticator
 * @covers \App\Service\Security\Authentication\GraphQLAuthenticator
 */
class PasswordAuthenticatorTest extends TestCase
{
    public function test_supports(): void
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag([
            '_graphql_credentials' => ['username' => 'asdf', 'password' => 'asdf'],
        ]);

        $authenticator = new PasswordAuthenticator();
        $this->assertTrue($authenticator->supports($request));

        $request->attributes = new ParameterBag([
            '_graphql_credentials' => ['username' => 'asdf', 'passcode' => 'asdf'],
        ]);

        $this->assertFalse($authenticator->supports($request));
    }

    public function test_authenticate(): void
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag([
            '_graphql_credentials' => ['username' => 'username', 'password' => '123456'],
        ]);

        $authenticator = new PasswordAuthenticator();
        $passport = $authenticator->authenticate($request);

        $badge = $passport->getBadge(PasswordCredentials::class);
        $this->assertInstanceOf(PasswordCredentials::class, $badge);
        $this->assertSame('123456', $badge->getPassword());
    }
}
