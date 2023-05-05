<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\Entity\Permission;
use App\GraphQL\TypeRegistry;
use App\Service\Security\Authorization\AuthorizationCheckerTrait;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class DashboardQuery extends FieldDefinition
{
    use AuthorizationCheckerTrait;

    public function __construct(TypeRegistry $type, CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'dashboard',
            'type' => $type->dashboard(),
            // null will cause the dashboard to not display any results
            'resolve' => function () use ($currentUser) {
                if ($this->isGranted($currentUser, Permission::VIEW_DASHBOARD)) {
                    return [];
                }

                // this will cause the dashboard to be empty, but without an error
                return null;
            },
            'description' => 'Get current dashboard',
        ]);
    }
}
