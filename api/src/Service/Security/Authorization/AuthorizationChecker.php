<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization;

use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Simplified authorization checker that can be used not only during request.
 * It is not aware of the current request, so it cannot use the current user.
 *
 * @api
 */
class AuthorizationChecker
{
    public function __construct(private AccessDecisionManagerInterface $accessDecisionManager)
    {
    }

    public function isGranted(UserInterface $user, string $permission, mixed $subject = null): bool
    {
        return $this->accessDecisionManager->decide(new AuthorizationToken($user), [$permission], $subject);
    }

    public function denyAccessUnlessGranted(UserInterface $user, string $permission, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($user, $permission, $subject)) {
            $exception = new AccessDeniedException($message);
            $exception->setAttributes([$permission]);
            $exception->setSubject($subject);

            throw $exception;
        }
    }
}
