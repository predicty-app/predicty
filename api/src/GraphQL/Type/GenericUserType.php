<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\User;
use App\GraphQL\TypeRegistry;
use App\Repository\AccountRepository;
use App\Service\Predicty\PredictySettings;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use RuntimeException;

class GenericUserType extends InterfaceType
{
    public function __construct(TypeRegistry $type, AccountRepository $accountRepository, PredictySettings $predictySettings)
    {
        parent::__construct([
            'name' => 'GenericUser',
            'resolveType' => fn ($value): ObjectType => match (true) {
                $value instanceof CurrentUser => $type->currentUser(),
                $value instanceof User => $type->user(),
                default => throw new RuntimeException('Unexpected User type: '.$value::class),
            },
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'email' => [
                    'type' => $type->string(),
                ],
                'isEmailVerified' => [
                    'type' => $type->boolean(),
                    'deprecationReason' => 'Use hasVerifiedEmail instead',
                    'resolve' => fn (User $user) => $user->hasVerifiedEmail(),
                ],
                'isOnboardingComplete' => [
                    'type' => $type->boolean(),
                    'deprecationReason' => 'Use hasCompletedOnboarding instead',
                    'resolve' => fn (User $user) => $user->hasCompletedOnboarding(),
                ],
                'hasCompletedOnboarding' => [
                    'type' => $type->boolean(),
                    'resolve' => fn (User $user) => $user->hasCompletedOnboarding(),
                ],
                'hasVerifiedEmail' => [
                    'type' => $type->boolean(),
                    'resolve' => fn (User $user) => $user->hasVerifiedEmail(),
                ],
                'hasAgreedToNewsletter' => [
                    'type' => $type->boolean(),
                ],
                'hasAcceptedTermsOfService' => [
                    'type' => $type->boolean(),
                    'resolve' => fn (User $user) => $user->hasAgreedToTerms($predictySettings->getCurrentTermsOfServiceVersion()),
                ],
                'acceptedTermsOfServiceVersion' => [
                    'type' => $type->int(),
                ],
                'accounts' => fn () => [
                    'type' => $type->listOf($type->account()),
                    'resolve' => fn (User $user) => $accountRepository->findAllByIds($user->getAccountsIds()),
                    'description' => 'All accounts that the user is a member of',
                ],
                'roles' => [
                    'type' => $type->listOf($type->string()),
                    'resolve' => fn (User $user) => $user->getRoles(),
                ],
            ],
        ]);
    }
}
