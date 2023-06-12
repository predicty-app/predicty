<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\DoctrineUser;
use App\Entity\Role;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Entity\DoctrineUser
 */
class UserTest extends TestCase
{
    public function test_get_accounts_ids(): void
    {
        $user = $this->createUser();
        $this->assertEquals([], $user->getAccountsIds());

        $account1Id = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $account2Id = Ulid::fromString('01H1VH1W58G5MW6C2487ZPAB5F');

        $user->setAsAccountMember($account1Id);
        $this->assertEquals([$account1Id], $user->getAccountsIds());

        $user->setAsAccountOwner($account2Id);
        $this->assertEquals([$account1Id, $account2Id], $user->getAccountsIds());
    }

    public function test_belongs_to_account(): void
    {
        $account1Id = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');
        $account2Id = Ulid::fromString('01H1VH1W58G5MW6C2487ZPAB5F');

        $user = $this->createUser();
        $user->setAsAccountMember($account1Id);

        $this->assertTrue($user->isMemberOf($account1Id));
        $this->assertFalse($user->isMemberOf($account2Id));
    }

    public function test_set_as_account_member(): void
    {
        $account1Id = Ulid::fromString('01H1VECDYVB5BRQVPTSVJP3BZA');

        $user = $this->createUser();
        $user->setAsAccountMember($account1Id);

        $this->assertEquals([$account1Id], $user->getAccountsIds());
        $this->assertEquals([Role::ROLE_ACCOUNT_MEMBER], $user->getRolesForAccount($account1Id));
    }

    public function test_set_as_account_owner(): void
    {
        $account2Id = Ulid::fromString('01H1VH1W58G5MW6C2487ZPAB5F');

        $user = $this->createUser();
        $user->setAsAccountOwner($account2Id);
        $this->assertEquals([$account2Id], $user->getAccountsIds());
        $this->assertEquals([Role::ROLE_ACCOUNT_OWNER], $user->getRolesForAccount($account2Id));
    }

    private function createUser(): DoctrineUser
    {
        $id = Ulid::fromString('01H1VEC8SYM3K6TSDAPFN25XZV');

        return new DoctrineUser($id, 'john.doe@example.com');
    }
}
