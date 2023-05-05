<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\TypeRegistry;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class MeQuery extends FieldDefinition
{
    public function __construct(TypeRegistry $type, CurrentUser $user)
    {
        parent::__construct([
            'name' => 'me',
            'type' => $type->user(),
            'resolve' => fn () => $user,
            'description' => 'Find currently logged in user',
        ]);
    }
}
