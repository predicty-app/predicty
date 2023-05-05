<?php

declare(strict_types=1);

namespace App\Entity;

use ReflectionClass;
use ReflectionClassConstant;

final class Role
{
    /**
     * Super admin role is for internal users, who can manage the system.
     */
    public const ROLE_SYSTEM_SUPER_ADMIN = 'ROLE_SYSTEM_SUPER_ADMIN';

    /**
     * Admin role is for internal users, who can manage the system.
     */
    public const ROLE_SYSTEM_ADMIN = 'ROLE_SYSTEM_ADMIN';

    /**
     * Default role for account owners.
     */
    public const ROLE_ACCOUNT_OWNER = 'ROLE_ACCOUNT_OWNER';

    /**
     * Default role for account members.
     */
    public const ROLE_ACCOUNT_MEMBER = 'ROLE_ACCOUNT_MEMBER';

    /**
     * Default role.
     */
    public const ROLE_USER = 'ROLE_USER';

    // special

    public const IS_AUTHENTICATED = 'IS_AUTHENTICATED';
    public const IS_AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';
    public const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';
    public const IS_AUTHENTICATED_REMEMBERED = 'IS_AUTHENTICATED_REMEMBERED';
    public const IS_REMEMBERED = 'IS_REMEMBERED';
    public const IS_IMPERSONATOR = 'IS_IMPERSONATOR';

    private const SYSTEM_ROLES_PREFIX = 'ROLE_SYSTEM_';
    private const ROLES_PREFIX = 'ROLE_';

    public static function assertValid(string $role): void
    {
        assert(in_array($role, self::getRoles(), true), sprintf('Invalid role "%s"', $role));
    }

    public static function getRoles(): array
    {
        return array_keys(
            array_filter(
                (new ReflectionClass(self::class))->getConstants(ReflectionClassConstant::IS_PUBLIC),
                fn (string $role) => str_starts_with($role, self::ROLES_PREFIX)
            )
        );
    }

    public static function isSystemRole(string $role): bool
    {
        return str_starts_with($role, self::SYSTEM_ROLES_PREFIX);
    }
}
