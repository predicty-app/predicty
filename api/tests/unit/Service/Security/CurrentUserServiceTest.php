<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security;

use App\Entity\Account;
use App\Entity\User;
use App\Service\Security\Account\AccountContextProvider;
use App\Service\Security\CurrentUserService;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Service\Security\CurrentUserService
 */
class CurrentUserServiceTest extends TestCase
{
    public function test_is_allowed_to(): void
    {
        $security = $this->createMock(Security::class);
        $security->expects($this->once())->method('isGranted')
            ->with('foo', 'bar')
            ->willReturn(true);

        $context = $this->createMock(AccountContextProvider::class);
        $service = new CurrentUserService($security, $context);

        $service->isAllowedTo('foo', 'bar');
    }

    public function test_get_account_roles(): void
    {
        $accountId = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        $account = $this->createMock(Account::class);
        $account->expects($this->once())->method('getId')
            ->willReturn($accountId);

        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('getRolesForAccount')
            ->with($accountId)
            ->willReturn(['ROLE_USER']);

        $security = $this->createMock(Security::class);
        $security->expects($this->once())->method('isGranted')->with('IS_AUTHENTICATED')->willReturn(true);
        $security->expects($this->once())->method('getUser')->willReturn($user);

        $context = $this->createMock(AccountContextProvider::class);
        $context->expects($this->once())->method('getCurrentlySelectedAccount')
            ->willReturn($account);

        $service = new CurrentUserService($security, $context);

        $this->assertSame(['ROLE_USER'], $service->getAccountRoles());
    }

    public function test_is_the_same_user_as(): void
    {
        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($userId);

        $security = $this->createMock(Security::class);
        $security->expects($this->exactly(1))->method('getUser')->willReturn($user);
        $security->expects($this->once())->method('isGranted')->with('IS_AUTHENTICATED')->willReturn(true);

        $context = $this->createMock(AccountContextProvider::class);
        $service = new CurrentUserService($security, $context);

        $this->assertTrue($service->isTheSameUserAs($user));
    }

    public function test_is_anonymous(): void
    {
        $security = $this->createMock(Security::class);
        $security->expects($this->once())->method('isGranted')->with('IS_AUTHENTICATED')->willReturn(false);

        $context = $this->createMock(AccountContextProvider::class);
        $service = new CurrentUserService($security, $context);

        $this->assertTrue($service->isAnonymous());
    }
}
