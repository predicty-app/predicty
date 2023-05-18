<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\User;
use App\GraphQL\TypeRegistry;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\ObjectType;

class CurrentUserType extends ObjectType
{
    public function __construct(TypeRegistry $type, CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'CurrentUser',
            'interfaces' => [
                $type->genericUser(),
            ],
            'fields' => array_merge(
                $type->user()->getFields(),
                [
                    'account' => fn () => [
                        'type' => $type->account(),
                        'resolve' => fn (User $user) => $user instanceof CurrentUser ? $user->getAccount() : null,
                        'description' => 'Account that is currently used by the user',
                    ],
                    'actions' => [
                        'type' => $type->userActions(),
                        'resolve' => fn (User $user) => $currentUser->isTheSameUserAs($user) ? $currentUser->getActions() : [],
                        'description' => 'Actions that should be taken by the user',
                    ],
                ]
            ),
        ]);
    }
}
