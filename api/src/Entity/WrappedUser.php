<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Represents a user that is a proxy for the real user entity.
 */
interface WrappedUser
{
    public function getUser(): User;
}
