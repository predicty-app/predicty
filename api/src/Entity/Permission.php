<?php

declare(strict_types=1);

namespace App\Entity;

use ReflectionClass;
use ReflectionClassConstant;

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

    public const ADD_AD_TO_AD_COLLECTION = 'ADD_AD_TO_AD_COLLECTION';
    public const REMOVE_AD_FROM_AD_COLLECTION = 'REMOVE_AD_FROM_AD_COLLECTION';
    public const CREATE_AD_COLLECTION = 'CREATE_AD_COLLECTION';

    public const WITHDRAW_IMPORT = 'WITHDRAW_IMPORT';

    public const CREATE_ACCOUNT = 'CREATE_ACCOUNT';
    public const MANAGE_ACCOUNT = 'MANAGE_ACCOUNT';
    public const SWITCH_ACCOUNT = 'SWITCH_ACCOUNT';

    public const DOWNLOAD_IMPORTED_FILE = 'DOWNLOAD_IMPORTED_FILE';

    /**
     * Get all available permissions.
     */
    public static function getPermissions(): array
    {
        return array_keys((new ReflectionClass(self::class))->getConstants(ReflectionClassConstant::IS_PUBLIC));
    }
}
