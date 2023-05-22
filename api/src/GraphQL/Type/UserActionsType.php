<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\UserActions;
use App\GraphQL\TypeRegistry;
use App\Service\Predicty\PredictySettings;
use GraphQL\Type\Definition\ObjectType;

class UserActionsType extends ObjectType
{
    public function __construct(TypeRegistry $type, PredictySettings $predictySettings)
    {
        parent::__construct([
            'name' => 'UserActions',
            'fields' => [
                'hasToAcceptTermsOfService' => [
                    'type' => $type->boolean(),
                    'resolve' => fn (UserActions $actions) => $actions->hasToAcceptTermsOfService($predictySettings->getCurrentTermsOfServiceVersion()),
                    'description' => 'If terms of service have changed, user has to accept them again.',
                ],
                'hasToVerifyEmail' => [
                    'type' => $type->boolean(),
                ],
                'hasToCompleteOnboarding' => [
                    'type' => $type->boolean(),
                ],
            ],
        ]);
    }
}
