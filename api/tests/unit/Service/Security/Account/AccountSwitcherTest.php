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
use Symfony\Component\Uid\Ulid;

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
    private Ulid $account1Id;
    private Ulid $account2Id;

    protected function setUp(): void
    {
        parent::setUp();

        $userId = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $this->account1Id = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');
        $this->account2Id = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');

        $this->account1 = $this->createMock(Account::class);
        $this->account1->method('getId')->willReturn($this->account1Id);

        $this->account2 = $this->createMock(Account::class);
        $this->account2->method('getId')->willReturn($this->account2Id);

        $repository = $this->createMock(AccountRepository::class);
        $repository->method('findById')->willReturnMap([
            [$this->account1Id, $this->account1],
            [$this->account2Id, $this->account2],
        ]);

        $this->user = $this->createMock(User::class);
        $this->user->method('getId')->willReturn($userId);

        $this->storage = new InMemoryStorage();
        $this->switcher = new AccountSwitcher($repository, $this->storage);
    }

    public function test_switch_using_account_id(): void
    {
        $this->user->method('isMemberOf')->willReturn(true);
        $this->switcher->switchAccount($this->user, $this->account2Id);

        $this->assertEquals(['01H1VECDYVB5BRQVPTSVJP3BZA' => $this->account2Id], $this->storage->mockData);
    }

    public function test_switch_using_account_instance(): void
    {
        $this->user->method('isMemberOf')->willReturn(true);
        // @phpstan-ignore-next-line
        $this->switcher->switchAccount($this->user, $this->account2);

        $this->assertEquals(['01H1VECDYVB5BRQVPTSVJP3BZA' => $this->account2Id], $this->storage->mockData);
    }

    public function test_get_currently_selected_account(): void
    {
        $this->user->method('isMemberOf')->willReturn(true);
        $this->switcher->switchAccount($this->user, $this->account2Id);

        $account = $this->switcher->getCurrentlySelectedAccount($this->user);
        $this->assertSame($this->account2, $account);
    }

    public function test_get_default_account(): void
    {
        $this->user->method('getAccountsIds')->willReturn([$this->account1Id]);
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
        $this->expectExceptionMessage('Unable to switch accounts - user does not belong to account "01H1VECDYVB5BRQVPTSVJP3BZA');

        $this->user->method('isMemberOf')->willReturn(false);
        $this->switcher->switchAccount($this->user, $this->account2Id);
    }
}
