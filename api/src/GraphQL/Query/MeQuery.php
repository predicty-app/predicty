<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\Resolver\UserResolver;
use App\GraphQL\TypeResolver;
use GraphQL\Type\Definition\FieldDefinition;

class MeQuery extends FieldDefinition
{
    public function __construct(UserResolver $resolver, TypeResolver $type)
    {
        parent::__construct([
            'name' => 'me',
            'type' => $type->user(),
            'resolve' => fn () => $resolver->findCurrentlyLoggedInUser(),
            'description' => 'Find currently logged in user',
        ]);
    }
}
