<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * This entity controls what actions can be performed by the UI in the context of the given user.
 */
class UserActions
{
    public function __construct(private User $user)
    {
    }

    public function hasToAcceptTermsOfService(int $currentVersion): bool
    {
        return $this->user->hasAgreedToTerms($currentVersion) === false;
    }

    public function hasToVerifyEmail(): bool
    {
        return $this->user->hasVerifiedEmail() === false;
    }

    public function hasToCompleteOnboarding(): bool
    {
        return $this->user->hasCompletedOnboarding() === false;
    }
}
