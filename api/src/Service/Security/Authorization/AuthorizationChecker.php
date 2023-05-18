<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization;

use App\Service\Security\CurrentUser;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
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
        // anonymous users are not allowed to do anything
        // we are using the null token here, as current user is a wrapper that tries to initialize the real user object
        // resulting in an exception if the user is not logged in which in result breaks permission checking
        if ($user instanceof CurrentUser && $user->isAnonymous()) {
            $token = new NullToken();
        } else {
            $token = new AuthorizationToken($user);
        }

        return $this->accessDecisionManager->decide($token, [$permission], $subject);
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
