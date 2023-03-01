<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;

class MutationType extends ObjectType
{
    public function __construct(iterable $mutationRootFields)
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [...$mutationRootFields],
        ]);
    }
}
