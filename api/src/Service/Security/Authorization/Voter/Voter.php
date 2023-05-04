<?php

declare(strict_types=1);

namespace App\Service\Security\Authorization\Voter;

use App\Entity\User;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * @template T of object
 */
abstract class Voter implements VoterInterface, CacheableVoterInterface
{
    /**
     * @param null|T        $subject
     * @param array<string> $attributes
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $vote = self::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            $vote = self::ACCESS_DENIED;
            $user = $token->getUser();

            if ($user !== null && !$user instanceof User) {
                throw new RuntimeException('User must be instance of '.User::class.' or null');
            }

            if ($this->voteOnAttribute($attribute, $subject, $user)) {
                // grant access as soon as at least one attribute returns a positive response
                return self::ACCESS_GRANTED;
            }
        }

        return $vote;
    }

    public function supportsAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->getSupportedPermissions(), true);
    }

    public function supportsType(string $subjectType): bool
    {
        return
            $subjectType === $this->getSupportedType()
            // this is important as sometimes we will get doctrine proxy classes
            || is_subclass_of($subjectType, $this->getSupportedType())
            // symfony asks about nulls, we allow them by default and leave it up to the voter to decide
            || $subjectType === 'null';
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
