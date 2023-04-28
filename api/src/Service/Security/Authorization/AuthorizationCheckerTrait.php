<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization;

use App\Entity\User;
use Symfony\Contracts\Service\Attribute\Required;

trait AuthorizationCheckerTrait
{
    private ?AuthorizationChecker $authorizationChecker = null;

    #[Required]
    public function setAuthorizationChecker(AuthorizationChecker $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function isGranted(User $user, string $attribute, mixed $subject = null): bool
    {
        assert($this->authorizationChecker instanceof AuthorizationChecker, 'AuthorizationChecker is not set');

        return $this->authorizationChecker->isGranted($user, $attribute, $subject);
    }

    public function denyAccessUnlessGranted(User $user, mixed $attribute, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        assert($this->authorizationChecker instanceof AuthorizationChecker, 'AuthorizationChecker is not set');
        $this->authorizationChecker->denyAccessUnlessGranted($user, $attribute, $subject, $message);
    }
}
