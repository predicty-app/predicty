<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use App\Entity\UserWithId;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * A service representing the currently logged-in user.
 * Current user is available only in the context of a request.
 */
interface CurrentUser extends UserWithId
{
    /**
     * @throws CustomUserMessageAuthenticationException If user is not logged in
     */
    public function getId(): int;

    /**
     * @throws CustomUserMessageAuthenticationException If user is not logged in
     */
    public function getUser(): User;

    /**
     * Helper method to check if the user is logged in.
     */
    public function isAnonymous(): bool;

    /**
     * Helper method to check if the user has a specific permission.
     */
    public function isAllowedTo(string $permission, mixed $subject): bool;
}
