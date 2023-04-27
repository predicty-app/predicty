<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @api
 */
class AuthorizationChecker
{
    public function __construct(private AccessDecisionManagerInterface $accessDecisionManager)
    {
    }

    public function isGranted(User $user, string $attribute, mixed $subject = null): bool
    {
        return $this->accessDecisionManager->decide(new AuthorizationToken($user), [$attribute], $subject);
    }

    public function denyAccessUnlessGranted(User $user, mixed $attribute, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($user, $attribute, $subject)) {
            $exception = new AccessDeniedException($message);
            $exception->setAttributes([$attribute]);
            $exception->setSubject($subject);

            throw $exception;
        }
    }
}
