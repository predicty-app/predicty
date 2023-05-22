<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\Entity\Role;
use App\GraphQL\TypeRegistry;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class MeQuery extends FieldDefinition
{
    use AuthorizationCheckerTrait;

    public function __construct(TypeRegistry $type, CurrentUser $user)
    {
        parent::__construct([
            'name' => 'me',
            'type' => $type->currentUser(),
            'resolve' => function () use ($user) {
                if ($this->isGranted($user, Role::IS_AUTHENTICATED)) {
                    return $user;
                }

                return null;
            },
            'description' => 'Find currently logged in user',
        ]);
    }
}
