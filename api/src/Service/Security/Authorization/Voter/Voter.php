<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\Account;
use App\Entity\AccountAwareUser;
use App\Entity\User;
use App\Entity\UserOwnable;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @template T of object
 */
abstract class Voter implements VoterInterface, CacheableVoterInterface
{
    private ?User $user = null;
    private LoggerInterface $logger;

    public function __construct(
        private RoleHierarchyInterface $roleHierarchy,
        ?LoggerInterface $securityLogger = null
    ) {
        $this->logger = $securityLogger ?? new NullLogger();
    }

    /**
     * @param null|T        $subject
     * @param array<string> $attributes
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $vote = self::ACCESS_ABSTAIN;
        $user = $token->getUser();

        foreach ($attributes as $attribute) {
            $vote = self::ACCESS_DENIED;

            if ($user !== null && !$user instanceof User) {
                throw new RuntimeException('User must be an instance of '.User::class.' or null');
            }

            $this->user = $user;
            if ($this->voteOnAttribute($attribute, $subject, $user)) {
                // grant access as soon as at least one attribute returns a positive response
                $vote = self::ACCESS_GRANTED;

                break;
            }
        }

        $message = match ($vote) {
            self::ACCESS_GRANTED => 'Voter "%s" granted access to %s for attributes "%s" on subject "%s"',
            self::ACCESS_DENIED => 'Voter "%s" denied access to %s for attributes "%s" on subject "%s"',
            default => 'Voter "%s" abstained from voting to %s for attributes "%s" on subject "%s"',
        };

        $subjectName = get_debug_type($subject);
        if (is_object($subject) && method_exists($subject, 'getId')) {
            $subjectName = sprintf('%s #%s', $subjectName, $subject->getId());
        }

        $this->logger->debug(sprintf(
            $message,
            static::class,
            $user ? sprintf('user "%s"', $user->getUserIdentifier()) : 'an anonymous user',
            implode(', ', $attributes),
            $subjectName
        ));

        return $vote;
    }

    public function supportsAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->getSupportedPermissions(), true);
    }

    public function supportsType(string $subjectType): bool
    {
        if ($this->isNullSubjectAllowed() && $subjectType === 'null') {
            return true;
        }

        $supportedType = $this->getSupportedType();

        return $subjectType === $supportedType || is_subclass_of($subjectType, $supportedType);
    }

    /**
     * Helper method to check if the current user has a specific role.
     * Note, that this also checks for role hierarchy.
     *
     * This method implies that the user is present, therefore it will return false otherwise.
     * If no account is provided, voter will attempt to get it from the user.
     */
    protected function hasRole(string $role, Account|Ulid|null $account = null): bool
    {
        if ($this->user === null) {
            return false;
        }

        $roles = $this->user->getRoles();

        // if account is not provided, try to get it from the user
        if ($account === null && $this->user instanceof AccountAwareUser) {
            $account = $this->user->getAccount();
        }

        if ($account !== null) {
            // getRoles will return roles assigned to the user in the current account context,
            // therefore we need to check for permissions that the user will have with the subject account and not the one from the context
            $roles = $this->user->getRolesForAccount($account);
        }

        return in_array($role, $this->roleHierarchy->getReachableRoleNames($roles), true);
    }

    /**
     * Helper method to check if the current user is an owner of the given subject.
     * For this method to work, subject must be an instance of {@see UserOwnable}.
     *
     * This method implies that the user is present, therefore it will return false otherwise.
     *
     * @see UserOwnable
     */
    protected function isAnOwnerOf(mixed $subject): bool
    {
        if ($this->user === null) {
            return false;
        }

        if ($subject instanceof UserOwnable) {
            return $subject->isOwnedBy($this->user);
        }

        throw new RuntimeException('Subject must be an instance of '.UserOwnable::class);
    }

    /**
     * Override this method to change the default behavior of the voter.
     * This will allow the voter to handle permission checks for null subjects.
     */
    protected function isNullSubjectAllowed(): bool
    {
        return false;
    }

    /**
     * @return class-string<T>
     */
    abstract protected function getSupportedType(): string;

    /**
     * @return array<string>
     */
    abstract protected function getSupportedPermissions(): array;

    /**
     * @param null|T $subject
     */
    abstract protected function voteOnAttribute(string $permission, mixed $subject, ?User $user): bool;
}
