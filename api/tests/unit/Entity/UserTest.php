<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\DoctrineUser;
use App\Entity\Role;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\DoctrineUser
 */
class UserTest extends TestCase
{
    public function test_get_accounts_ids(): void
    {
        $user = new DoctrineUser('john.doe@example.com');
        $this->assertEquals([], $user->getAccountsIds());

        $user->setAsAccountMember(400);
        $this->assertEquals([400], $user->getAccountsIds());

        $user->setAsAccountOwner(500);
        $this->assertEquals([400, 500], $user->getAccountsIds());
    }

    public function test_belongs_to_account(): void
    {
        $user = new DoctrineUser('john.doe@example.com');
        $user->setAsAccountMember(400);

        $this->assertTrue($user->isMemberOf(400));
        $this->assertFalse($user->isMemberOf(500));
    }

    public function test_set_as_account_member(): void
    {
        $user = new DoctrineUser('john.doe@example.com');
        $user->setAsAccountMember(400);

        $this->assertEquals([400], $user->getAccountsIds());
        $this->assertEquals([Role::ROLE_ACCOUNT_MEMBER], $user->getRolesForAccount(400));
    }

    public function test_set_as_account_owner(): void
    {
        $user = new DoctrineUser('john.doe@example.com');
        $user->setAsAccountOwner(500);
        $this->assertEquals([500], $user->getAccountsIds());
        $this->assertEquals([Role::ROLE_ACCOUNT_OWNER], $user->getRolesForAccount(500));
    }
}
