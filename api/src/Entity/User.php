<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Represents a user.
 *
 * - To manipulate only user password, use the {@see PasswordAuthenticatedUser} interface.
 * - To see, if the user is in a context of an account check for {@see AccountAwareUser} interface.
 * - If you need only user id, check for {@see UserWithId} interface.
 * - User also implements the {@see EmailRecipient} interface, so it can be used to send emails.
 * - User also implements the {@see UserInterface} from Symfony, so it can be used in symfony internal logic.
 */
interface User extends UserWithId, AccountMember, EmailRecipient, UserInterface, PasswordAuthenticatedUser
{
    public function getUuid(): Uuid;

    public function hasVerifiedEmail(): bool;

    public function hasCompletedOnboarding(): bool;

    public function getChangedAt(): DateTimeInterface;

    public function getCreatedAt(): DateTimeInterface;

    public function setAsAccountOwner(int $accountId): void;

    public function setAsAccountMember(int $accountId): void;

    public function setEmailVerified(): void;

    public function setOnboardingComplete(): void;

    public function hasAgreedToNewsletter(): bool;

    public function setAgreedToNewsletter(): void;

    public function hasAgreedToTerms(int $version): bool;

    public function setAgreedToTerms(int $version): void;

    public function getAcceptedTermsOfServiceVersion(): int;
}
