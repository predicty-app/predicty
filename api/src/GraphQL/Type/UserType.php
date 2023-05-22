<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class UserType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'User',
            'interfaces' => [
                $type->genericUser(),
            ],
            'fields' => $type->genericUser()->getFields(),
        ]);
    }
}
