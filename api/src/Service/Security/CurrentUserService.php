<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * @internal This class should only be used by the Security component. {@link CurrentUser} should be used instead.
 */
class CurrentUserService implements CurrentUser
{
    public function __construct(private Security $security)
    {
    }

    public function getId(): int
    {
        return $this->getUser()->getId();
    }

    public function getUser(): User
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            return $user;
        }

        $exception = new CustomUserMessageAuthenticationException('User was requested but none was logged in');
        $exception->setSafeMessage('You are not logged in');

        throw $exception;
    }

    public function isAnonymous(): bool
    {
        return $this->security->getUser() === null;
    }

    public function isAllowedTo(string $permission, mixed $subject): bool
    {
        return $this->security->isGranted($permission, $subject);
    }
}
