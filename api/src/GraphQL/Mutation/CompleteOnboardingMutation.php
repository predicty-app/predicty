<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\CompleteOnboarding;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\FieldDefinition;

class CompleteOnboardingMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, CurrentUserService $currentUserService)
    {
        parent::__construct([
            'name' => 'completeOnboarding',
            'type' => $type->string(),
            'args' => [],
            'resolve' => fn (mixed $root, array $args) => $this->handle(new CompleteOnboarding($currentUserService->getId())) ?? 'OK',
            'description' => 'Complete onboarding for currently logged in user',
        ]);
    }
}
