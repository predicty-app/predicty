<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Permission;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\Permission
 */
class PermissionTest extends TestCase
{
    public function test_get_permissions(): void
    {
        $this->assertSame(
            [
                'START_CONVERSATION',
                'REMOVE_CONVERSATION',
                'ADD_CONVERSATION_COMMENT',
                'EDIT_CONVERSATION_COMMENT',
                'REMOVE_CONVERSATION_COMMENT',
                'ADD_AD_TO_AD_COLLECTION',
                'REMOVE_AD_FROM_AD_COLLECTION',
                'CREATE_AD_COLLECTION',
                'WITHDRAW_IMPORT',
                'CREATE_ACCOUNT',
                'MANAGE_ACCOUNT',
                'SWITCH_ACCOUNT',
                'DOWNLOAD_IMPORTED_FILE',
                'VIEW_DASHBOARD',
            ],
            Permission::getPermissions()
        );
    }
}
