<?php

declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;

/**
 * List of permission attributes.
 */
final class Permission
{
    public const START_CONVERSATION = 'START_CONVERSATION';
    public const REMOVE_CONVERSATION = 'REMOVE_CONVERSATION';
    public const ADD_CONVERSATION_COMMENT = 'ADD_CONVERSATION_COMMENT';
    public const EDIT_CONVERSATION_COMMENT = 'EDIT_CONVERSATION_COMMENT';
    public const REMOVE_CONVERSATION_COMMENT = 'REMOVE_CONVERSATION_COMMENT';

    public const ALL = [
        self::EDIT_CONVERSATION_COMMENT,
        self::REMOVE_CONVERSATION_COMMENT,
        self::START_CONVERSATION,
        self::REMOVE_CONVERSATION,
        self::ADD_CONVERSATION_COMMENT,
    ];

    public static function isValid(string $permission): bool
    {
        return in_array($permission, self::ALL, true);
    }

    public static function validate(string $permission): void
    {
        if (!self::isValid($permission)) {
            throw new InvalidArgumentException(sprintf('Invalid permission "%s".', $permission));
        }
    }
}
