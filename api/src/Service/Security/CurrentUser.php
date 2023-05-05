<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\AccountAwareUser;
use App\Entity\User;
use App\Entity\UserWithId;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * A service representing the currently logged-in user and is also aware of currently selected account.
 * It is available only in the context of a request. It is not available in the console context.
 */
interface CurrentUser extends User, AccountAwareUser
{
    /**
     * @throws CustomUserMessageAuthenticationException If user is not logged in
     */
    public function getId(): int;

    /**
     * Get inner {@see User} object.
     *
     * @throws CustomUserMessageAuthenticationException If user is not logged in
     */
    public function getUser(): User;

    /**
     * Helper method to check if the user is logged in.
     */
    public function isAnonymous(): bool;

    /**
     * Tells if currently logged-in user is the same as the given user.
     */
    public function isTheSameUserAs(UserWithId $user): bool;

    /**
     * Helper method to check if the user has a specific permission.
     * - For a list of permissions, see {@see Permission}.
     */
    public function isAllowedTo(string $permission, mixed $subject): bool;
}
