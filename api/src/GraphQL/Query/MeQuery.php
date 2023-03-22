<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\TypeRegistry;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\FieldDefinition;

class MeQuery extends FieldDefinition
{
    public function __construct(TypeRegistry $type, CurrentUserService $currentUserService)
    {
        parent::__construct([
            'name' => 'me',
            'type' => $type->user(),
            'resolve' => fn () => $currentUserService->getUser(),
            'description' => 'Find currently logged in user',
        ]);
    }
}
