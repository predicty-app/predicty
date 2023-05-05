<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security\Account;

use App\Entity\Account;
use App\Entity\User;
use App\Repository\AccountRepository;
use App\Service\Security\Account\AccountSwitcher;
use App\Service\Security\Account\Storage\InMemoryStorage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * @covers \App\Service\Security\Account\AccountSwitcher
 */
class AccountSwitcherTest extends TestCase
{
    private User&MockObject $user;
    private AccountSwitcher $switcher;
    private InMemoryStorage $storage;
    private Account|MockObject $account1;
    private Account|MockObject $account2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->account1 = $this->createMock(Account::class);
        $this->account1->method('getId')->willReturn(123);

        $this->account2 = $this->createMock(Account::class);
        $this->account2->method('getId')->willReturn(456);

        $repository = $this->createMock(AccountRepository::class);
        $repository->method('findById')->willReturnMap([
            [123, $this->account1],
            [456, $this->account2],
        ]);

        $this->user = $this->createMock(User::class);
        $this->user->method('getId')->willReturn(1);

        $this->storage = new InMemoryStorage();
        $this->switcher = new AccountSwitcher($repository, $this->storage);
    }

    public function test_switch_using_account_id(): void
    {
        $this->user->method('isMemberOf')->willReturn(true);
        $this->switcher->switchAccount($this->user, 456);

        $this->assertSame(['1' => 456], $this->storage->mockData);
    }

    public function test_switch_using_account_instance(): void
    {
        $this->user->method('isMemberOf')->willReturn(true);
        // @phpstan-ignore-next-line
        $this->switcher->switchAccount($this->user, $this->account2);

        $this->assertSame(['1' => 456], $this->storage->mockData);
    }

    public function test_get_currently_selected_account(): void
    {
        $this->user->method('isMemberOf')->willReturn(true);
        $this->switcher->switchAccount($this->user, 456);

        $account = $this->switcher->getCurrentlySelectedAccount($this->user);
        $this->assertSame($this->account2, $account);
    }

    public function test_get_default_account(): void
    {
        $this->user->method('getAccountsIds')->willReturn([123]);
        $account = $this->switcher->getCurrentlySelectedAccount($this->user);
        $this->assertSame($this->account1, $account);
    }

    public function test_get_null_if_no_accounts_found(): void
    {
        $this->user->method('getAccountsIds')->willReturn([]);
        $account = $this->switcher->getCurrentlySelectedAccount($this->user);
        $this->assertNull($account);
    }

    public function test_switch_to_account_that_user_is_not_a_member_of(): void
    {
        $this->expectException(CustomUserMessageAuthenticationException::class);
        $this->expectExceptionMessage('Unable to switch accounts - user does not belong to account "456');

        $this->user->method('isMemberOf')->willReturn(false);
        $this->switcher->switchAccount($this->user, 456);
    }
}
