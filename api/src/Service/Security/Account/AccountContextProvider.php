<?php

declare(strict_types=1);

namespace App\Service\Security\Account;

use App\Entity\Account;
use App\Entity\AccountMember;
use App\Entity\UserWithId;

/**
 * Returns the information about the currently selected account.
 */
interface AccountContextProvider
{
    /**
     * Get currently selected account or default, if possible.
     */
    public function getCurrentlySelectedAccount(UserWithId&AccountMember $user): ?Account;
}
