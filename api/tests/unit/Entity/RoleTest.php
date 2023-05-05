<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Role;
use AssertionError;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\Role
 */
class RoleTest extends TestCase
{
    public function test_get_roles(): void
    {
        $this->assertSame(
            [
                'ROLE_SYSTEM_SUPER_ADMIN',
                'ROLE_SYSTEM_ADMIN',
                'ROLE_ACCOUNT_OWNER',
                'ROLE_ACCOUNT_MEMBER',
                'ROLE_USER',
            ],
            Role::getRoles()
        );
    }

    public function test_assert_valid_on_validd_role(): void
    {
        $this->expectNotToPerformAssertions();
        Role::assertValid('ROLE_USER');
    }

    public function test_assert_valid_on_invalid_role(): void
    {
        $this->expectException(AssertionError::class);
        $this->expectExceptionMessage('Invalid role "ROLE_INVALID"');

        Role::assertValid('ROLE_INVALID');
    }
}
