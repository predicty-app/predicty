<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait AuthorizationCheckerTrait
{
    private ?AuthorizationChecker $authorizationChecker = null;

    #[Required]
    public function setAuthorizationChecker(AuthorizationChecker $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function isGranted(UserInterface $user, string $attribute, mixed $subject = null): bool
    {
        assert($this->authorizationChecker instanceof AuthorizationChecker, 'AuthorizationChecker is not set');

        return $this->authorizationChecker->isGranted($user, $attribute, $subject);
    }

    public function denyAccessUnlessGranted(UserInterface $user, mixed $attribute, mixed $subject = null, string $message = 'Access Denied.'): void
    {
        assert($this->authorizationChecker instanceof AuthorizationChecker, 'AuthorizationChecker is not set');
        $this->authorizationChecker->denyAccessUnlessGranted($user, $attribute, $subject, $message);
    }
}
